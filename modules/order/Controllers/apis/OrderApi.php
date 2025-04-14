<?php
#------------------------------------------------------------------
# OrderApi.php
# 오더 API
# 김우진
# 2024-11-18 16:54:49
# @Desc :
#------------------------------------------------------------------
namespace Module\order\Controllers\apis;

use Module\order\Controllers\OrderApis;

use Shared\Config as SharedConfig;
#------------------------------------------------------------------
# FIXME: editor 데이터 html 변환라이브러리
#------------------------------------------------------------------
use League\CommonMark\CommonMarkConverter;

class OrderApi extends OrderApis
{


    public function __construct()
    {
        parent::__construct();

        $this->db                                   = \Config\Database::connect();

    }

    public function updateShipInfo()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => 'ordIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문 고유값 누락.',
                ],
            ],
            'shipNum' => [
                'label'  => 'shipNum',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '운송장 번호 누락.',
                ],
            ],
            'shipCompany' => [
                'label'  => 'shipCompany',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '택배사 정보 누락.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }


        $modelParam                                 = [];
        $modelParam['O_IDX']                        = _elm( $requests, 'ordIdx' );
        $modelParam['O_SHIP_NUMBER']                = _elm( $requests, 'shipNum' );
        $modelParam['O_SHIP_COMPANY']               = _elm( $requests, 'shipCompany' );
        $aData                                      = $this->orderModel->getOrderDataByIdx( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문정보가 없습니다.';

            return $this->respond( $response );
        }

        $this->db->transBegin();

        $aStatus                                    = $this->orderModel->updateShipInfo( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '송장정보 업데이트 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '운송장 정보 수정  - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) .' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '저장 되었습니다.';

        return $this->respond( $response );
    }


    public function orderStatusChange()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());


        $orderStatus                                = $this->sharedConfig::$orderStatus;

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'o_i_idx' => [
                'label'  => '',
                'rules'  => 'required',
                'errors' => [
                    'required' => '주문상품을 선택해주세요.',
                ],
            ],
            'i_status' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상태를 선택해주세요.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $modelParam                                 = [];
        $modelParam['O_IDXS']                       = _elm( $requests, 'o_i_idx' );
        $modelParam['O_STATUS']                     = _elm( $requests, 'i_status' );
        $aDatas                                     = $this->orderModel->getOrderDataByIdxArr( $modelParam );

        if( empty( $aDatas ) === true  ){
            $response['status']                     = 400;
            $response['alert']                      = '상품데이터가 없습니다.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 주문번호 순번을 뽑기 위한 데이터세팅
        #------------------------------------------------------------------
        $allDatas                                   = $this->orderModel->getOrderInfoByOrdID( _elm( _elm( $aDatas, 0 ), 'O_ORDID' ) );

        #------------------------------------------------------------------
        # TODO: 원본 상태값 검수
        #------------------------------------------------------------------
        $nLoop                                      = 1;
        if( count( $aDatas ) == 1 ){
            $nLoop                                  = null;
        }
        $sendOrderTotAmt                            = 0;
        $orderNumTxt                                = [];

        $aOrderOgiginData                           = $this->orderModel->getOrderOriginByOrdID( _elm( _elm($aDatas, 0), 'O_ORDID' ) ) ;

        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderOgiginData, 'O_MB_IDX' ) );

        foreach( $aDatas as $aData ){
            if( _elm( $aData, 'O_STATUS' ) == _elm( $requests, 'i_status' ) ){
                $response['status']                 = 400;
                if( $nLoop != null ){
                    $response['alert']              = '['._elm( $aData, 'O_ORDID' ).'-'.$nLoop.'] 변경하려는 상태와 동일합니다. ';
                }else{
                    $response['alert']              = '['._elm( $aData, 'O_ORDID' ).'] 변경하려는 상태와 동일합니다. ';
                }
                return $this->respond( $response );
                break;
            }
            $sendOrderTotAmt                       += _elm( $aData, 'O_TOTAL_PRICE' );
            $targetOIdx                             = _elm( $aData, 'O_IDX' );

            #------------------------------------------------------------------
            # TODO: 주문번호 텍스트 - 순번 찾기
            #------------------------------------------------------------------
            $index                                  = array_search($targetOIdx, array_column($allDatas, 'O_IDX'));

            if( $nLoop != null ){
                $nLoop ++;
                $orderNumTxt[]                      = _elm( $aData, 'O_ORDID' ).'-'.($index+1);
            }else{
                $orderNumTxt[]                      = _elm( $aData, 'O_ORDID' );
            }
        }

        $this->db->transBegin();

        $aStatus                                    = $this->orderModel->orderStatusChangeMultiple( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상태변경 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 알림톡 발송
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_aTalkWithSendFlag' ) == 'Y' || _elm( $requests, 'i_aTalkOnlySendFlag' ) == 'Y' ){

            if( _elm( $requests, 'i_status' ) == 'PayComplete' ){

                $kakaoParam                         = [];
                $kakaoParam['temp_name']            = '입금확인';
                $kakaoParam['mobile_num']           = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
                $kakaoParam['fields']               = [];
                $kakaoParam['fields']['mall_name']  = '산수유람';
                $kakaoParam['fields']['order_name'] = _elm( _elm( $aDatas, 0 ), 'O_NAME' );
                $kakaoParam['fields']['oder_amount']= number_format( $sendOrderTotAmt );
                $kakaoParam['fields']['order_num']  = implode( ', ', $orderNumTxt );
                $kakaoParam['fields']['short_url']  = shop_url().'mypage/inquiry';
                if( _elm( $requests, 'i_aTalkWithSendFlag' ) == 'Y' ){
                    $kakaoParam['MB_IDX']           = _elm( $memberInfo, 'MB_IDX' );
                    $kakaoParam['FCM_SEND']         = true;
                }
            }else{
                $kakaoParam                         = [];
                $kakaoParam['temp_name']            = '주문상태변경';
                $kakaoParam['mobile_num']           = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
                $kakaoParam['fields']               = [];
                $kakaoParam['fields']['mall_name']  = '산수유람';
                $kakaoParam['fields']['order_name'] = _elm( _elm( $aDatas, 0 ), 'O_NAME' );
                $kakaoParam['fields']['order_status']= _elm( $orderStatus,_elm( $requests, 'i_status' ) );
                $kakaoParam['fields']['order_num']  = implode( ', ', $orderNumTxt );
                $kakaoParam['fields']['short_url']  = shop_url().'mypage/order-history';
                if( _elm( $requests, 'i_aTalkWithSendFlag' ) == 'Y' ){
                    $kakaoParam['MB_IDX']           = _elm( $memberInfo, 'MB_IDX' );
                    $kakaoParam['FCM_SEND']         = true;
                }

            }

            $aStatus = $this->pushSms( $kakaoParam );

        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '주문 상태변경 ( '._elm( $requests, 'i_status' ).' ) - orgData:'.json_encode( $aDatas, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '저장 되었습니다.';

        return $this->respond( $response );

    }


    public function orderStatusDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aOrderData                                 = $this->orderModel->getOrderDetail( _elm( $requests, 'ordIdx' ) );
        if( empty( $aOrderData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문데이터가 없습니다.';

            return $this->respond( $response );
        }



        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $aOrderData, 'O_ORDID' ) );


        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문 상세데이터가 없습니다.';

            return $this->respond( $response );
        }

        $deliveryInfo                               = []; // 배송 정보를 저장할 배열
        $cnt                                        = count( $orderInfo );
        foreach( $orderInfo as $aKey => $aInfo ){
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $aInfo, 'O_IDX' ) );


            if( empty( $orderGoodsInfo ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '주문 상품 데이터가 없습니다.';

                return $this->respond( $response );
            }
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $orderInfo[$aKey]['ordIdPrd']           = $cnt > 1 ? _elm( $aInfo, 'O_ORDID' ).'-'.($aKey+1) : _elm( $aInfo, 'O_ORDID' );
            $orderInfo[$aKey]['goodsImg']           = _elm( $goodsImg, 'I_IMG_PATH' );
            $orderInfo[$aKey]['goodsColor']         = _elm( $color, 'G_COLOR' );
            $orderInfo[$aKey]['goodsInfo']          = $orderGoodsInfo;

            #------------------------------------------------------------------
            # TODO: 배송지 세팅
            #------------------------------------------------------------------
            $deliveryData                           = [
                'O_ORDIDPRD'                        => _elm( $aInfo, 'O_ORDID' ) ,
                'O_RCV_NAME'                        => _elm( $aInfo, 'O_RCV_NAME' ),
                'O_ZIPCODE'                         => _elm( $aInfo, 'O_ZIPCODE' ),
                'O_ADDRESS1'                        => _elm( $aInfo, 'O_ADDRESS1' ),
                'O_ADDRESS2'                        => _elm( $aInfo, 'O_ADDRESS2' ),
                'O_RCV_MOBILE_NUM'                  =>  _add_dash_tel_num( $this->_aesDecrypt( _elm( $aInfo, 'O_RCV_MOBILE_NUM' ) ) ),
                'O_SHIP_MSG'                        => _elm( $aInfo, 'O_SHIP_MSG' ),
            ];

            #------------------------------------------------------------------
            # TODO: 기존 배송 정보와 비교하여 동일한 배송 정보가 있으면 배열에 추가하지 않음
            #------------------------------------------------------------------
            $isDuplicate = false;
            foreach ($deliveryInfo as $existingDelivery) {
                if ($existingDelivery === $deliveryData) {
                    $isDuplicate = true;
                    break; // 중복되면 더 이상 비교할 필요 없음
                }
            }

            // 중복되지 않으면 배열에 추가
            if (!$isDuplicate) {
                $deliveryInfo[] = $deliveryData;
            }

        }
        if( count( $deliveryInfo ) > 1 ){
            foreach( $deliveryInfo as $dKey => $delivery ){
                $deliveryInfo[$dKey]['O_ORDIDPRD']  = _elm( $delivery, 'O_ORDIDPRD' ).($dKey+1);
            }
        }

        $aOrderData['orderInfos']                   = $orderInfo;
        #------------------------------------------------------------------
        # TODO: 회원정보 가져오기
        #------------------------------------------------------------------
        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderData, 'O_MB_IDX' ) );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '구매자 정보 누락';

            return $this->respond( $response );
        }
        $memberInfo['MB_MOBILE_DEC']                =  _add_dash_tel_num( $this->_aesDecrypt( _elm(  $memberInfo, 'MB_MOBILE_NUM' ) ) );
        $memberInfo['MB_EMAIL_DEC']                 =  $this->_aesDecrypt( _elm(  $memberInfo, 'MB_EMAIL' ) );

        $_member_grade                              = $this->memberModel->getMembershipGrade();


        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $aData                                  = $aOrderData;

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['aData']                    = $aData;
            $view_datas['aMemberInfo']              = $memberInfo;
            $view_datas['aMemberGrade']             = $member_grade;
            $view_datas['aDeliveryInfo']            = $deliveryInfo;

            $this->owensView->setViewDatas( $view_datas );
            $page_datas['detail']                   = view( '\Module\order\Views\payStatus\_detail' , ['owensView' => $this->owensView] );
        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------
        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aOrderData;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function orderStatusOverDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aOrderData                                 = $this->orderModel->getOrderDetail( _elm( $requests, 'ordIdx' ) );
        if( empty( $aOrderData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문데이터가 없습니다.';

            return $this->respond( $response );
        }



        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $aOrderData, 'O_ORDID' ) );


        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문 상세데이터가 없습니다.';

            return $this->respond( $response );
        }

        $deliveryInfo                               = []; // 배송 정보를 저장할 배열
        $cnt                                        = count( $orderInfo );
        foreach( $orderInfo as $aKey => $aInfo ){
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $aInfo, 'O_IDX' ) );


            if( empty( $orderGoodsInfo ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '주문 상품 데이터가 없습니다.';

                return $this->respond( $response );
            }
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $orderInfo[$aKey]['ordIdPrd']           = $cnt > 1 ? _elm( $aInfo, 'O_ORDID' ).'-'.($aKey+1) : _elm( $aInfo, 'O_ORDID' );
            $orderInfo[$aKey]['goodsImg']           = _elm( $goodsImg, 'I_IMG_PATH' );
            $orderInfo[$aKey]['goodsColor']         = _elm( $color, 'G_COLOR' );
            $orderInfo[$aKey]['goodsInfo']          = $orderGoodsInfo;

            #------------------------------------------------------------------
            # TODO: 배송지 세팅
            #------------------------------------------------------------------
            $deliveryData                           = [
                'O_ORDIDPRD'                        => _elm( $aInfo, 'O_ORDID' ) ,
                'O_RCV_NAME'                        => _elm( $aInfo, 'O_RCV_NAME' ),
                'O_ZIPCODE'                         => _elm( $aInfo, 'O_ZIPCODE' ),
                'O_ADDRESS1'                        => _elm( $aInfo, 'O_ADDRESS1' ),
                'O_ADDRESS2'                        => _elm( $aInfo, 'O_ADDRESS2' ),
                'O_RCV_MOBILE_NUM'                  =>  _add_dash_tel_num( $this->_aesDecrypt( _elm( $aInfo, 'O_RCV_MOBILE_NUM' ) ) ),
                'O_SHIP_MSG'                        => _elm( $aInfo, 'O_SHIP_MSG' ),
            ];

            #------------------------------------------------------------------
            # TODO: 기존 배송 정보와 비교하여 동일한 배송 정보가 있으면 배열에 추가하지 않음
            #------------------------------------------------------------------
            $isDuplicate = false;
            foreach ($deliveryInfo as $existingDelivery) {
                if ($existingDelivery === $deliveryData) {
                    $isDuplicate = true;
                    break; // 중복되면 더 이상 비교할 필요 없음
                }
            }

            // 중복되지 않으면 배열에 추가
            if (!$isDuplicate) {
                $deliveryInfo[] = $deliveryData;
            }

        }
        if( count( $deliveryInfo ) > 1 ){
            foreach( $deliveryInfo as $dKey => $delivery ){
                $deliveryInfo[$dKey]['O_ORDIDPRD']  = _elm( $delivery, 'O_ORDIDPRD' ).($dKey+1);
            }
        }

        $aOrderData['orderInfos']                   = $orderInfo;
        #------------------------------------------------------------------
        # TODO: 회원정보 가져오기
        #------------------------------------------------------------------
        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderData, 'O_MB_IDX' ) );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '구매자 정보 누락';

            return $this->respond( $response );
        }
        $memberInfo['MB_MOBILE_DEC']                =  _add_dash_tel_num( $this->_aesDecrypt( _elm(  $memberInfo, 'MB_MOBILE_NUM' ) ) );
        $memberInfo['MB_EMAIL_DEC']                 =  $this->_aesDecrypt( _elm(  $memberInfo, 'MB_EMAIL' ) );

        $_member_grade                              = $this->memberModel->getMembershipGrade();


        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $aData                                  = $aOrderData;

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['aData']                    = $aData;
            $view_datas['aMemberInfo']              = $memberInfo;
            $view_datas['aMemberGrade']             = $member_grade;
            $view_datas['aDeliveryInfo']            = $deliveryInfo;

            $this->owensView->setViewDatas( $view_datas );
            $page_datas['detail']                   = view( '\Module\order\Views\payStatusOver\_detail' , ['owensView' => $this->owensView] );
        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------
        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aOrderData;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function orderExchangeDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aOrderData                                 = $this->orderModel->getOrderDetail( _elm( $requests, 'ordIdx' ) );
        if( empty( $aOrderData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문데이터가 없습니다.';

            return $this->respond( $response );
        }



        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $aOrderData, 'O_ORDID' ) );


        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문 상세데이터가 없습니다.';

            return $this->respond( $response );
        }

        $deliveryInfo                               = []; // 배송 정보를 저장할 배열
        $cnt                                        = count( $orderInfo );
        foreach( $orderInfo as $aKey => $aInfo ){
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $aInfo, 'O_IDX' ) );


            if( empty( $orderGoodsInfo ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '주문 상품 데이터가 없습니다.';

                return $this->respond( $response );
            }
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $orderInfo[$aKey]['ordIdPrd']           = $cnt > 1 ? _elm( $aInfo, 'O_ORDID' ).'-'.($aKey+1) : _elm( $aInfo, 'O_ORDID' );
            $orderInfo[$aKey]['goodsImg']           = _elm( $goodsImg, 'I_IMG_PATH' );
            $orderInfo[$aKey]['goodsColor']         = _elm( $color, 'G_COLOR' );
            $orderInfo[$aKey]['goodsInfo']          = $orderGoodsInfo;

            #------------------------------------------------------------------
            # TODO: 배송지 세팅
            #------------------------------------------------------------------
            $deliveryData                           = [
                'O_ORDIDPRD'                        => _elm( $aInfo, 'O_ORDID' ) ,
                'O_RCV_NAME'                        => _elm( $aInfo, 'O_RCV_NAME' ),
                'O_ZIPCODE'                         => _elm( $aInfo, 'O_ZIPCODE' ),
                'O_ADDRESS1'                        => _elm( $aInfo, 'O_ADDRESS1' ),
                'O_ADDRESS2'                        => _elm( $aInfo, 'O_ADDRESS2' ),
                'O_RCV_MOBILE_NUM'                  =>  _add_dash_tel_num( $this->_aesDecrypt( _elm( $aInfo, 'O_RCV_MOBILE_NUM' ) ) ),
                'O_SHIP_MSG'                        => _elm( $aInfo, 'O_SHIP_MSG' ),
            ];

            #------------------------------------------------------------------
            # TODO: 기존 배송 정보와 비교하여 동일한 배송 정보가 있으면 배열에 추가하지 않음
            #------------------------------------------------------------------
            $isDuplicate = false;
            foreach ($deliveryInfo as $existingDelivery) {
                if ($existingDelivery === $deliveryData) {
                    $isDuplicate = true;
                    break; // 중복되면 더 이상 비교할 필요 없음
                }
            }

            // 중복되지 않으면 배열에 추가
            if (!$isDuplicate) {
                $deliveryInfo[] = $deliveryData;
            }

        }
        if( count( $deliveryInfo ) > 1 ){
            foreach( $deliveryInfo as $dKey => $delivery ){
                $deliveryInfo[$dKey]['O_ORDIDPRD']  = _elm( $delivery, 'O_ORDIDPRD' ).($dKey+1);
            }
        }

        $aOrderData['orderInfos']                   = $orderInfo;
        #------------------------------------------------------------------
        # TODO: 회원정보 가져오기
        #------------------------------------------------------------------
        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderData, 'O_MB_IDX' ) );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '구매자 정보 누락';

            return $this->respond( $response );
        }
        $memberInfo['MB_MOBILE_DEC']                =  _add_dash_tel_num( $this->_aesDecrypt( _elm(  $memberInfo, 'MB_MOBILE_NUM' ) ) );
        $memberInfo['MB_EMAIL_DEC']                 =  $this->_aesDecrypt( _elm(  $memberInfo, 'MB_EMAIL' ) );

        $_member_grade                              = $this->memberModel->getMembershipGrade();


        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $aData                                  = $aOrderData;

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['aData']                    = $aData;
            $view_datas['aMemberInfo']              = $memberInfo;
            $view_datas['aMemberGrade']             = $member_grade;
            $view_datas['aDeliveryInfo']            = $deliveryInfo;

            $this->owensView->setViewDatas( $view_datas );
            $page_datas['detail']                   = view( '\Module\order\Views\exchanges\_detail' , ['owensView' => $this->owensView] );
        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------
        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aOrderData;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }
    public function orderReturnsDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aOrderData                                 = $this->orderModel->getOrderDetail( _elm( $requests, 'ordIdx' ) );
        if( empty( $aOrderData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문데이터가 없습니다.';

            return $this->respond( $response );
        }



        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $aOrderData, 'O_ORDID' ) );


        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문 상세데이터가 없습니다.';

            return $this->respond( $response );
        }

        $deliveryInfo                               = []; // 배송 정보를 저장할 배열
        $cnt                                        = count( $orderInfo );
        foreach( $orderInfo as $aKey => $aInfo ){
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $aInfo, 'O_IDX' ) );


            if( empty( $orderGoodsInfo ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '주문 상품 데이터가 없습니다.';

                return $this->respond( $response );
            }
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $orderInfo[$aKey]['ordIdPrd']           = $cnt > 1 ? _elm( $aInfo, 'O_ORDID' ).'-'.($aKey+1) : _elm( $aInfo, 'O_ORDID' );
            $orderInfo[$aKey]['goodsImg']           = _elm( $goodsImg, 'I_IMG_PATH' );
            $orderInfo[$aKey]['goodsColor']         = _elm( $color, 'G_COLOR' );
            $orderInfo[$aKey]['goodsInfo']          = $orderGoodsInfo;

            #------------------------------------------------------------------
            # TODO: 배송지 세팅
            #------------------------------------------------------------------
            $deliveryData                           = [
                'O_ORDIDPRD'                        => _elm( $aInfo, 'O_ORDID' ) ,
                'O_RCV_NAME'                        => _elm( $aInfo, 'O_RCV_NAME' ),
                'O_ZIPCODE'                         => _elm( $aInfo, 'O_ZIPCODE' ),
                'O_ADDRESS1'                        => _elm( $aInfo, 'O_ADDRESS1' ),
                'O_ADDRESS2'                        => _elm( $aInfo, 'O_ADDRESS2' ),
                'O_RCV_MOBILE_NUM'                  =>  _add_dash_tel_num( $this->_aesDecrypt( _elm( $aInfo, 'O_RCV_MOBILE_NUM' ) ) ),
                'O_SHIP_MSG'                        => _elm( $aInfo, 'O_SHIP_MSG' ),
            ];

            #------------------------------------------------------------------
            # TODO: 기존 배송 정보와 비교하여 동일한 배송 정보가 있으면 배열에 추가하지 않음
            #------------------------------------------------------------------
            $isDuplicate = false;
            foreach ($deliveryInfo as $existingDelivery) {
                if ($existingDelivery === $deliveryData) {
                    $isDuplicate = true;
                    break; // 중복되면 더 이상 비교할 필요 없음
                }
            }

            // 중복되지 않으면 배열에 추가
            if (!$isDuplicate) {
                $deliveryInfo[] = $deliveryData;
            }

        }
        if( count( $deliveryInfo ) > 1 ){
            foreach( $deliveryInfo as $dKey => $delivery ){
                $deliveryInfo[$dKey]['O_ORDIDPRD']  = _elm( $delivery, 'O_ORDIDPRD' ).($dKey+1);
            }
        }

        $aOrderData['orderInfos']                   = $orderInfo;
        #------------------------------------------------------------------
        # TODO: 회원정보 가져오기
        #------------------------------------------------------------------
        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderData, 'O_MB_IDX' ) );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '구매자 정보 누락';

            return $this->respond( $response );
        }
        $memberInfo['MB_MOBILE_DEC']                =  _add_dash_tel_num( $this->_aesDecrypt( _elm(  $memberInfo, 'MB_MOBILE_NUM' ) ) );
        $memberInfo['MB_EMAIL_DEC']                 =  $this->_aesDecrypt( _elm(  $memberInfo, 'MB_EMAIL' ) );

        $_member_grade                              = $this->memberModel->getMembershipGrade();


        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $aData                                  = $aOrderData;

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['aData']                    = $aData;
            $view_datas['aMemberInfo']              = $memberInfo;
            $view_datas['aMemberGrade']             = $member_grade;
            $view_datas['aDeliveryInfo']            = $deliveryInfo;

            $this->owensView->setViewDatas( $view_datas );
            $page_datas['detail']                   = view( '\Module\order\Views\returns\_detail' , ['owensView' => $this->owensView] );
        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------
        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aOrderData;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function orderDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aOrderData                                 = $this->orderModel->getOrderDetail( _elm( $requests, 'ordIdx' ) );
        if( empty( $aOrderData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문데이터가 없습니다.';

            return $this->respond( $response );
        }



        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $aOrderData, 'O_ORDID' ) );


        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '주문 상세데이터가 없습니다.';

            return $this->respond( $response );
        }

        $deliveryInfo                               = []; // 배송 정보를 저장할 배열
        $cnt                                        = count( $orderInfo );
        foreach( $orderInfo as $aKey => $aInfo ){
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $aInfo, 'O_IDX' ) );


            if( empty( $orderGoodsInfo ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '주문 상품 데이터가 없습니다.';

                return $this->respond( $response );
            }
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $orderInfo[$aKey]['ordIdPrd']           = $cnt > 1 ? _elm( $aInfo, 'O_ORDID' ).'-'.($aKey+1) : _elm( $aInfo, 'O_ORDID' );
            $orderInfo[$aKey]['goodsImg']           = _elm( $goodsImg, 'I_IMG_PATH' );
            $orderInfo[$aKey]['goodsColor']         = _elm( $color, 'G_COLOR' );
            $orderInfo[$aKey]['goodsInfo']          = $orderGoodsInfo;

            #------------------------------------------------------------------
            # TODO: 배송지 세팅
            #------------------------------------------------------------------
            $deliveryData                           = [
                'O_ORDIDPRD'                        => _elm( $aInfo, 'O_ORDID' ) ,
                'O_RCV_NAME'                        => _elm( $aInfo, 'O_RCV_NAME' ),
                'O_ZIPCODE'                         => _elm( $aInfo, 'O_ZIPCODE' ),
                'O_ADDRESS1'                        => _elm( $aInfo, 'O_ADDRESS1' ),
                'O_ADDRESS2'                        => _elm( $aInfo, 'O_ADDRESS2' ),
                'O_RCV_MOBILE_NUM'                  =>  _add_dash_tel_num( $this->_aesDecrypt( _elm( $aInfo, 'O_RCV_MOBILE_NUM' ) ) ),
                'O_SHIP_MSG'                        => _elm( $aInfo, 'O_SHIP_MSG' ),
            ];

            #------------------------------------------------------------------
            # TODO: 기존 배송 정보와 비교하여 동일한 배송 정보가 있으면 배열에 추가하지 않음
            #------------------------------------------------------------------
            $isDuplicate = false;
            foreach ($deliveryInfo as $existingDelivery) {
                if ($existingDelivery === $deliveryData) {
                    $isDuplicate = true;
                    break; // 중복되면 더 이상 비교할 필요 없음
                }
            }

            // 중복되지 않으면 배열에 추가
            if (!$isDuplicate) {
                $deliveryInfo[] = $deliveryData;
            }

        }
        if( count( $deliveryInfo ) > 1 ){
            foreach( $deliveryInfo as $dKey => $delivery ){
                $deliveryInfo[$dKey]['O_ORDIDPRD']  = _elm( $delivery, 'O_ORDIDPRD' ).($dKey+1);
            }
        }

        $aOrderData['orderInfos']                   = $orderInfo;
        #------------------------------------------------------------------
        # TODO: 회원정보 가져오기
        #------------------------------------------------------------------
        $memberInfo                                 = $this->memberModel->getMembershipDataByIdx( _elm( $aOrderData, 'O_MB_IDX' ) );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '구매자 정보 누락';

            return $this->respond( $response );
        }
        $memberInfo['MB_MOBILE_DEC']                =  _add_dash_tel_num( $this->_aesDecrypt( _elm(  $memberInfo, 'MB_MOBILE_NUM' ) ) );
        $memberInfo['MB_EMAIL_DEC']                 =  $this->_aesDecrypt( _elm(  $memberInfo, 'MB_EMAIL' ) );

        $_member_grade                              = $this->memberModel->getMembershipGrade();


        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $aData                                  = $aOrderData;

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['aData']                    = $aData;
            $view_datas['aMemberInfo']              = $memberInfo;
            $view_datas['aMemberGrade']             = $member_grade;
            $view_datas['aDeliveryInfo']            = $deliveryInfo;

            $this->owensView->setViewDatas( $view_datas );
            $page_datas['detail']                   = view( '\Module\order\Views\order\_detail' , ['owensView' => $this->owensView] );
        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------
        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aOrderData;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function getOrderLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $orderStatusConfig                          = $this->sharedConfig::$orderStatus;
        $payMethodConfig                            = $this->sharedConfig::$paymentMethods;
        $bankCode                                   = $this->sharedConfig::$portOneBankCode;
        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }


        $odrStatus                                  = [
            #------------------------------------------------------------------
            # TODO: 결제대기
            #------------------------------------------------------------------
            'payWait'                               => [
                'PayRequest', 'PayWaiting', 'PayChecking',
            ],
            #------------------------------------------------------------------
            # TODO: 상품준비중
            #------------------------------------------------------------------
            'prdWait'                               => [
                'ProductWaiting',
            ],
            #------------------------------------------------------------------
            # TODO: 취소접수
            #------------------------------------------------------------------
            'cancel'                                => [
                'PayFailed', 'PayCancelRequest', 'PayCancelComplete', 'UnpaidCancelComplete', 'OrderCancelComplete', 'ShippingRefundRequest', 'ShippingRefundFailed',
                'ShippingRefundComplete', 'ShippingCancelRequestBuyer', 'ShippingCancelRequestSeller',
            ],
            #------------------------------------------------------------------
            # TODO: 반품접수
            #------------------------------------------------------------------
            'return'                                => [
                'ReturnPending', 'ReturnShippingProgress', 'ReturnRequest', 'ReturnPickupRequest', 'ReturnApproved', 'ReturnShippingComplete', 'ReturnCancelRequest',
                'ReturnCancelComplete', 'ReturnRefundRequest', 'ReturnRefundWaiting', 'ReturnRefundFailed', 'ReturnRefundComplete', ''
            ],
            #------------------------------------------------------------------
            # TODO: 교환접수
            #------------------------------------------------------------------
            'exchange'                              => [
                'ExchangeRequest', 'ExchangePickupRequest', 'ExchangeApproved', 'ExchangePending', 'ExchangeReturning', 'ExchangeReturnComplete', 'ExchangeShippingProgress',
                'ExchangeShippingComplete', 'ExchangeRejected'
            ],
            #------------------------------------------------------------------
            # TODO: 배송대기
            #------------------------------------------------------------------
            'shipWait'                              => [
                'ShippingRequest', 'ShippingWaiting',
            ],
            #------------------------------------------------------------------
            # TODO: 배송중
            #------------------------------------------------------------------
            'shippingProgress'                      => [
                'ShippingProgress'
            ],
            #------------------------------------------------------------------
            # TODO: 배송완료
            #------------------------------------------------------------------
            'shippingComplate'                      => [
                'ShippingComplete'
            ],
            #------------------------------------------------------------------
            # TODO: 구매결정
            #------------------------------------------------------------------
            'buyDecision'                           => [
                'BuyDecision',
            ],
        ];

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        if( empty(  _elm( $requests, 's_condition' ) ) === false &&  empty(  _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'orderNo' :
                    $modelParam['ORDID']            = _elm( $requests, 's_keyword' );
                    break;
                case 'memberId' :
                    $modelParam['MB_USERID']        = _elm( $requests, 's_keyword' );
                    break;
                case 'prdName':
                    $modelParam['GOODS_NAME']       = _elm( $requests, 's_keyword' );
                    break;
                case 'memberName' :
                    $modelParam['MB_NM']            = _elm( $requests, 's_keyword' );
                    break;
                case 'mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( preg_replace('/[-\s]/', '',_elm( $requests, 's_keyword' ) ) );
                    break;

            }
        }

        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['O_STATUS']                 = _elm( $requests, 's_status' );
        }else if( empty( _elm( $requests, 's_ordStatus' ) ) === false ){
            $modelParam['O_STATUS']                 = _elm( $odrStatus, _elm( $requests, 's_ordStatus' ) );

        }

        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['START_DATE']               = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['END_DATE']                 = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }else{
            $modelParam['START_DATE']               = date( 'Y-m-d' );
            $modelParam['END_DATE']                 = date( 'Y-m-d' );
        }

        $modelParam['order']                        = 'OO.O_ORDER_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->orderModel->getOrderLists($modelParam);
        //print_R( $aLISTS_RESULT );
        #------------------------------------------------------------------
        # TODO: 상태별 카운트 를 위한 상태 배열
        #------------------------------------------------------------------
        $memberGradeInfo                            = $this->memberModel->getMembershipGrade();




        $allCnt                                     = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => [] ], $modelParam);
        $payWaitCnt                                 = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'payWait' ) ], $modelParam );            //결제대기
        $prdWaitCnt                                 = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'prdWait' ) ], $modelParam );            //상품준비중
        $cancelCnt                                  = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'cancel' ) ], $modelParam );             //취소접수
        $returnCnt                                  = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'return' ) ], $modelParam );             //반품접수
        $exchangeCnt                                = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'exchange' ) ], $modelParam );           //교환접수
        $shipWaitCnt                                = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'shipWait' ) ], $modelParam );           //배송대기
        $ShipProgressCnt                            = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'shippingProgress' ) ], $modelParam );   //배송중
        $shipComplateCnt                            = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'shippingComplate' ) ], $modelParam );   //배송완료
        $buyDecisionCnt                             = $this->orderModel->getOrderStatusCnt( [ 'O_STATUS' => _elm( $odrStatus, 'buyDecision' ) ], $modelParam );        //구매결정
        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){

            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                // 상태별로 그룹화된 상품 정보를 저장할 배열
                $groupedGoodsInfo = [];
                $pgInfo                             = [];
                $priceInfo                          = [];
                $goodsInfo                          = [];
                $deliveryInfo                       = [];
                $orderStatus                        = [];
                $thumb                              = [];
                $orderClaim                         = [];
                #------------------------------------------------------------------
                # TODO: 주문건별 ORDER_INFO 데이터 로드
                #------------------------------------------------------------------
                $orderInfos                         = $this->orderModel->getOrderInfoByOrdID( _elm( $lists, 'O_ORDID' ) );

                $memGrande = array_filter($memberGradeInfo, function ($item) use ($lists) {
                    return _elm($item, 'G_NAME') == _elm($lists, 'G_NAME');
                });

                $pgInfo['payMethod']                = empty( _elm( $lists, 'O_PAY_METHOD' ) ) == false || _elm( $lists, 'O_PG_PRICE' ) > 0 ? _elm( $payMethodConfig, _elm( $lists, 'O_PAY_METHOD', null, true ), null, true ) : ( _elm( $lists, 'O_USE_MILEAGE' ) > 0 ? '포인트' : '기타' ) ?? '기타' ;
                $pgInfo['totalPrdAmt']              = _elm( $lists, 'O_TOTAL_PRICE' );
                $pgInfo['totalPgAmt']               = _elm( $lists, 'O_PG_PRICE' );
                $pgInfo['usePoint']                 = _elm( $lists, 'O_USE_MILEAGE' );
                if( _elm( $lists, 'O_PAY_METHOD' ) == 'VirtualBank' ){
                    $pgInfo['virtualBankInfo']      = [
                        'bankNm'                    => empty( _elm( $lists, 'O_PG_BANK_CODE' ) ) === true ? '' : array_search( _elm( $lists, 'O_PG_BANK_CODE' ), $bankCode ),
                        'accountNum'            => _elm( $lists, 'O_PG_ACCOUNT_NUM' ),
                    ];
                }
                else if( _elm( $lists, 'O_PAY_METHOD' ) == 'AccountTransfer' ){
                    $pgInfo['virtualBankInfo']      = [
                        'bankNm'                    => empty( _elm( $lists, 'O_PG_BANK_CODE' ) ) === true ? '' : array_search( _elm( $lists, 'O_PG_BANK_CODE' ), $bankCode ),
                        'accountNum'            => _elm( $lists, 'O_PG_ACCOUNT_NUM' ),
                    ];
                }

                $pgInfo['savePoint']            = _elm( $lists, 'O_SAVE_MILEAGE' );


                if( empty( $orderInfos ) === true ){
                    $response['status']             = 400;
                    $response['alert']              = '연결된 주문 데이터가 없습니다.';

                    return $this->respond( $response );
                }

                foreach( $orderInfos as $gKey => $orderInfo ){
                    $orderGoodsInfo                 = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm($orderInfo, 'O_IDX') );

                    if( empty( $orderGoodsInfo ) === true ){
                        $response['status']         = 400;
                        $response['alert']          = '연결된 주문상품 데이터가 없습니다.';

                        return $this->respond( $response );
                    }
                    $claimInfo                      = $this->orderModel->getOrderClaim( _elm($orderInfo, 'O_IDX') );
                    if( empty( $claimInfo ) === false  ){

                        $orderClaim[$gKey]          = [
                            'type'                  => _elm( $claimInfo, 'H_TYPE' ),
                            'status'                => _elm( $claimInfo, 'H_STATUS' ),
                            'resultAmt'             => _elm( $claimInfo, 'H_RESULT_AMT' ),
                        ];
                    }
                    $goodsData                      = $this->goodsModel->getGoodsDataByIdx( _elm( $orderGoodsInfo, 'P_PRID') );

                    if( _elm( $goodsInfo, 'G_GRADE_DISCOUNT_FLAG' ) == 'Y' ){
                        $mDcAmtPer                  = _elm( $memGrande, 'dcRate' ) / 100;
                        $mDcAmt                     = _elm( $orderInfo, 'O_ORIGIN_PRICE' ) * $mDcAmtPer;
                        $mDcAmt                     = round( $mDcAmt, -1 );
                        $priceInfo[ $gKey ]['gradeDcAmt'] = $mDcAmt;
                    }
                    if( empty( _elm( $orderInfo, 'O_USE_CPN_IDX' ) ) === false ){
                        $priceInfo[ $gKey ]['cpnDcAmt'] = _elm( $orderInfo, 'O_USE_CPN_MINUS_PRICE' );
                    }

                    if( empty( _elm( $orderInfo, 'O_USE_PLUS_CPN_INFO' ) ) === false ){
                        $priceInfo[ $gKey ]['plusCpnDcAmt'] = _elm( $orderInfo, 'O_USE_PLUS_CPN_MINUS_PRICE' );
                    }

                    $goodsImg                       = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), 100 );

                    $thumb[]                        = base_url()._elm( $goodsImg, 'I_IMG_PATH' );
                    // $goodsInfo[$gKey]               = [
                    //     'orderStatus'               => _elm($orderStatusConfig, _elm( $orderInfo, 'O_STATUS' ) ),
                    //     'thumb'                     => base_url()._elm( $goodsImg, 'I_IMG_PATH' ),
                    //     'cnt'                       => _elm($orderGoodsInfo, 'P_NUM'),
                    //     'title'                     => _elm($orderGoodsInfo, 'P_NAME'),
                    //     'option'                    => _elm($orderGoodsInfo, 'P_OPTION_NAME'),
                    //     'orderPrice'                => _elm( $orderInfo, 'O_TOTAL_PRICE' ),
                    // ];

                     // 상품 상태별로 그룹화

                    // 상태가 그룹화된 배열에 상품 정보를 추가
                    if (!isset($groupedGoodsInfo[_elm($orderInfo, 'O_STATUS')])) {
                        // 상태별로 배열이 존재하지 않으면 초기화
                        $groupedGoodsInfo[_elm($orderInfo, 'O_STATUS')] = [];
                    }
                    // 상태가 그룹화된 배열에 상품 정보를 추가
                    $groupedGoodsInfo[_elm($orderInfo, 'O_STATUS')][] = [
                        'ordIdx'      => _elm($orderInfo, 'O_IDX'),
                        'orderStatus' => _elm($orderStatusConfig, _elm($orderInfo, 'O_STATUS')),
                        'thumb' => base_url() . _elm($goodsImg, 'I_IMG_PATH'),
                        'cnt' => _elm($orderGoodsInfo, 'P_NUM'),
                        'title' => _elm($orderGoodsInfo, 'P_NAME'),
                        'option' => _elm($orderGoodsInfo, 'P_OPTION_NAME'),
                        'orderPrice' => _elm($orderInfo, 'O_TOTAL_PRICE'),
                    ];


                    $orderStatus[]                  = _elm( $orderInfo, 'O_STATUS' );
                    $deliveryInfo                   = [
                        'O_RCV_INFO'                => _elm( $orderInfo, 'O_RCV_NAME' ).' · '.$this->_aesDecrypt( _elm( $orderInfo, 'O_RCV_MOBILE_NUM' ) ) ,
                        'O_ADDRESS'                 => '('._elm( $orderInfo, 'O_ZIPCODE' ).') '._elm( $orderInfo, 'O_ADDRESS1' ). '  ' ._elm( $orderInfo, 'O_ADDRESS2' ),
                        'O_SHIP_MSG'                => _elm( $orderInfo, 'O_SHIP_MSG' ),
                    ];


                }
                $resultPriceInfo = [
                    'gradeDcAmt' => 0,
                    'cpnDcAmt' => 0,
                    'plusCpnDcAmt' => 0
                ];
                if( empty( $priceInfo ) === false ){
                    foreach ($priceInfo as $pKey => $info) {
                        if (isset($info['gradeDcAmt'])) {
                            $resultPriceInfo['gradeDcAmt']   += $info['gradeDcAmt']; // gradeDcAmt 합산
                        }
                        if (isset($info['cpnDcAmt'])) {
                            $resultPriceInfo['cpnDcAmt']     += $info['cpnDcAmt']; // cpnDcAmt 합산
                        }
                        if (isset($info['plusCpnDcAmt'])) {
                            $resultPriceInfo['plusCpnDcAmt'] += $info['plusCpnDcAmt']; // plusCpnDcAmt 합산
                        }
                    }
                }
                $pgInfo['priceInfo']                            = $resultPriceInfo;
                $aLISTS_RESULT['lists'][$aKey]['delivery']      = $deliveryInfo;
                $aLISTS_RESULT['lists'][$aKey]['status']        = $orderStatus;
                $aLISTS_RESULT['lists'][$aKey]['thumb']         = $thumb;
                $aLISTS_RESULT['lists'][$aKey]['goodsInfo']     = $groupedGoodsInfo;
                $aLISTS_RESULT['lists'][$aKey]['pgInfo']        = $pgInfo;
                $aLISTS_RESULT['lists'][$aKey]['claim']         = $orderClaim;
                #------------------------------------------------------------------
                # TODO: 메모 마지막꺼만 가져오기
                #------------------------------------------------------------------
                $aLISTS_RESULT['lists'][$aKey]['aMemo']         = $this->orderModel->getMemoLists( _elm( $lists, 'O_ORDID' ) );
                // print_r( $aLISTS_RESULT['lists'][$aKey]['aMemo']   );
                $aLISTS_RESULT['lists'][$aKey]['aMemoCnt']      = $this->orderModel->getMemoCnt( _elm( $lists, 'O_ORDID' ) );

            }
        }

        $page_datas                                 = [];

        $page_datas['allCnt']                       = empty($allCnt)           ? 0 : number_format($allCnt);
        $page_datas['payWaitCnt']                   = empty($payWaitCnt)       ? 0 : number_format($payWaitCnt);
        $page_datas['prdWaitCnt']                   = empty($prdWaitCnt)       ? 0 : number_format($prdWaitCnt);
        $page_datas['cancelCnt']                    = empty($cancelCnt)        ? 0 : number_format($cancelCnt);
        $page_datas['returnCnt']                    = empty($returnCnt)        ? 0 : number_format($returnCnt);
        $page_datas['exchangeCnt']                  = empty($exchangeCnt)      ? 0 : number_format($exchangeCnt);
        $page_datas['shipWaitCnt']                  = empty($shipWaitCnt)      ? 0 : number_format($shipWaitCnt);
        $page_datas['shipProgressCnt']              = empty($shipProgressCnt)  ? 0 : number_format($shipProgressCnt);
        $page_datas['shipCompleteCnt']              = empty($shipCompleteCnt)  ? 0 : number_format($shipCompleteCnt);
        $page_datas['buyDecisionCnt']               = empty($buyDecisionCnt)   ? 0 : number_format($buyDecisionCnt);
        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);




        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aOrderConf']               = $orderStatusConfig;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\order\Views\order\lists_v2_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/order/orderLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $page_datas['orderStatus']                  = $this->sharedConfig::$orderStatus;

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);

    }
    #------------------------------------------------------------------
    # TODO: 입금대기 리스트
    #------------------------------------------------------------------
    public function getOrderPayStatusLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        if( empty(  _elm( $requests, 's_condition' ) ) === false &&  empty(  _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'orderNo' :
                    $modelParam['ORDID']            = _elm( $requests, 's_keyword' );
                    break;
                case 'memberId' :
                    $modelParam['MB_USERID']        = _elm( $requests, 's_keyword' );
                    break;
                case 'memberName' :
                    $modelParam['MB_NM']            = _elm( $requests, 's_keyword' );
                    break;
                case 'mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( _elm( $requests, 's_keyword' ) );
                    break;

            }
        }

        if( empty( _elm( $requests, 's_paymethod' ) ) === false ){
            $modelParam['O_PAY_METHOD']             = _elm( $requests, 's_paymethod' );
        }

        if( empty( _elm( $requests, 's_ordStatus' ) ) === false ){
            $modelParam['O_STATUS']                 = explode( ',', _elm( $requests, 's_ordStatus' ) );
        }




        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['START_DATE']              = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['END_DATE']                = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        $modelParam['order']                        = 'OO.O_ORDER_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->orderModel->getOrderLists($modelParam);

        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){

            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                #------------------------------------------------------------------
                # TODO: 메모 마지막꺼만 가져오기
                #------------------------------------------------------------------
                $aLISTS_RESULT['lists'][$aKey]['aMemo']         = $this->orderModel->getLastMemo( _elm( $lists, 'O_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['aMemoCnt']      = $this->orderModel->getMemoCnt( _elm( $lists, 'O_IDX' ) );
            }
        }
        $statusArr                                  = [
            'wait' => [
                'PayRequest',           //'결제 요청',
                'PayWaiting',           //'결제 대기',
                'PayChecking',          //'입금 확인 중',
            ],
            'complete' => [
                'PayComplete',          // 결제완료
            ],
            'fail' => [
                'PayCancelRequest',     //'결제 취소 예정',
                'PayCancelComplete',    //'결제 취소 완료',
                'UnpaidCancelComplete', //'미입금 취소 완료',
                'OrderCancelComplete',  //'판매 취소 완료',
            ],
            'overStock' => [
                'ProductOutStock'       //'상품 품절',
            ],
        ];


        $statusParam                                = [];
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'wait', 0 );
        $waitCnt                                    = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'complete' );
        $compCnt                                    = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'fail' );
        $failCnt                                    = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'overStock' );
        $overStockCnt                               = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;



            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\order\Views\payStatus\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/order/orderLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['waitCnt']                        = $waitCnt;
        $response['compCnt']                        = $compCnt;
        $response['failCnt']                        = $failCnt;
        $response['overStockCnt']                   = $overStockCnt;


        $response['total_count']                    = $total_count;

        return $this->respond($response);

    }

    #------------------------------------------------------------------
    # TODO: 결제완료 리스트 이후 프로세스
    #------------------------------------------------------------------
    public function orderPrdProcessLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        if( empty(  _elm( $requests, 's_condition' ) ) === false &&  empty(  _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'orderNo' :
                    $modelParam['ORDID']            = _elm( $requests, 's_keyword' );
                    break;
                case 'memberId' :
                    $modelParam['MB_USERID']        = _elm( $requests, 's_keyword' );
                    break;
                case 'memberName' :
                    $modelParam['MB_NM']            = _elm( $requests, 's_keyword' );
                    break;
                case 'mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( _elm( $requests, 's_keyword' ) );
                    break;

            }
        }

        if( empty( _elm( $requests, 's_paymethod' ) ) === false ){
            $modelParam['O_PAY_METHOD']             = _elm( $requests, 's_paymethod' );
        }


        if ( empty(_elm($requests, 's_status') ) === true && !empty(_elm($requests, 's_ordStatus'))) {
            $modelParam['O_STATUS']                 = array_values(explode(',', _elm($requests, 's_ordStatus')));
        }else if( empty(_elm($requests, 's_status') ) === false ){
            $modelParam['O_STATUS']                 = _elm($requests, 's_status');
        }




        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['START_DATE']               = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['END_DATE']                 = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        $modelParam['order']                        = 'OO.O_ORDER_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->orderModel->getOrderInfoLists($modelParam);



        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){

            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                #------------------------------------------------------------------
                # TODO: 메모 마지막꺼만 가져오기
                #------------------------------------------------------------------

                $orderOriginInfo                                = $this->orderModel->getOrderOriginByOrdID( _elm( $lists, 'ORDER_ID' ) );
                $aLISTS_RESULT['lists'][$aKey]['ORDER_IDX']     = _elm( $orderOriginInfo, 'O_IDX' );
                $aLISTS_RESULT['lists'][$aKey]['aMemo']         = $this->orderModel->getLastMemo( _elm( $orderOriginInfo, 'O_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['aMemoCnt']      = $this->orderModel->getMemoCnt( _elm( $orderOriginInfo, 'O_IDX' ) );
            }
        }
        $statusArr                                  = [
            'complete' => [
                'PayComplete',
                'RefundRequest',                    //환불 요청 대기'
            ],
            'prdReady' => [
                'ProductWaiting',
            ],
            'shipReady' => [
                'ShippingWaiting',
            ],
            'shipping' => [
                'ShippingProgress',
            ],
            'shipComp' => [
                'ShippingComplete',
            ],
            'shipCancel' => [
                'ShippingCancelRequest',           //'결제 취소 예정',
                'ShippingCancelComplete',          //'결제 취소 완료',
                'ShippingRefundRequest',           //'배송 환불 요청 대기',
                'ShippingRefundWaiting',           //'환불 예정',
                'ShippingRefundFailed',            //'환불 재요청 대기',
                'ShippingRefundComplete',          //'환불 완료',
            ],
            'otherCancel' => [
                'ShippingCancelRequestBuyer',       //'구매자 배송 취소 요청',
                'ShippingCancelRequestSeller',      //'판매자 배송 취소 요청',
                'ShippingCancelRejected',           //'취소 불가(기발송)',
                'ShippingCancelRequestRetract',     //'취소 요청 철회',
            ],
            'buyDecs' => [
                'BuyDecision',
            ]
        ];

        $statusParam                                = [];
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'complete' );
        $completeCnt                                = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'prdReady' );
        $prdReadyCnt                                = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'shipReady' );
        $shipReadyCnt                               = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'shipping' );
        $shippingCnt                                = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'shipComp' );
        $shipCompCnt                                = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'shipCancel' );
        $shipCancelCnt                              = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'otherCancel' );
        $otherCancelCnt                             = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'buyDecs' );
        $buyDecsCnt                                 = $this->orderModel->getOrderInfoStatusCnt( $statusParam );


        $deliveryCompanys                           = [];
        $_deliveryCompanys                          = $this->orderModel->getDeliveryCompay();
        if( empty( $_deliveryCompanys ) == false ){
            foreach( $_deliveryCompanys as $dKey => $company ){
                $deliveryCompanys[_elm( $company, 'D_IDX' )]          = _elm( $company, 'D_COMPANY_NAME' );
            }
        }
        $deliverMain = current(
            array_column(
                array_filter($_deliveryCompanys, fn($company) =>
                    $company['D_FIX_YN'] === 'Y'
                ), 'D_IDX'
            )
        );

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $view_datas['deliveryCompanys']         = $deliveryCompanys;
            $view_datas['deliverMain']              = $deliverMain;




            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\order\Views\payStatusOver\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/order/orderLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['completeCnt']                    = $completeCnt;
        $response['prdReadyCnt']                    = $prdReadyCnt;
        $response['shipReadyCnt']                   = $shipReadyCnt;
        $response['shippingCnt']                    = $shippingCnt;
        $response['shipCompCnt']                    = $shipCompCnt;
        $response['shipCancelCnt']                  = $shipCancelCnt;
        $response['otherCancelCnt']                 = $otherCancelCnt;
        $response['buyDecsCnt']                     = $buyDecsCnt;



        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    #------------------------------------------------------------------
    # TODO: 교환프로세스 리스트
    #------------------------------------------------------------------
    public function orderExchangeProcessLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        if( empty(  _elm( $requests, 's_condition' ) ) === false &&  empty(  _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'orderNo' :
                    $modelParam['ORDID']            = _elm( $requests, 's_keyword' );
                    break;
                case 'memberId' :
                    $modelParam['MB_USERID']        = _elm( $requests, 's_keyword' );
                    break;
                case 'memberName' :
                    $modelParam['MB_NM']            = _elm( $requests, 's_keyword' );
                    break;
                case 'mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( _elm( $requests, 's_keyword' ) );
                    break;

            }
        }

        if( empty( _elm( $requests, 's_paymethod' ) ) === false ){
            $modelParam['O_PAY_METHOD']             = _elm( $requests, 's_paymethod' );
        }


        if ( empty(_elm($requests, 's_status') ) === true && !empty(_elm($requests, 's_ordStatus'))) {
            $modelParam['O_STATUS']                 = array_values(explode(',', _elm($requests, 's_ordStatus')));
        }else if( empty(_elm($requests, 's_status') ) === false ){
            $modelParam['O_STATUS']                 = _elm($requests, 's_status');
        }




        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['START_DATE']               = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['END_DATE']                 = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        $modelParam['order']                        = 'OO.O_ORDER_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->orderModel->getOrderInfoLists($modelParam);



        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){

            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                #------------------------------------------------------------------
                # TODO: 메모 마지막꺼만 가져오기
                #------------------------------------------------------------------
                $orderOriginInfo                                = $this->orderModel->getOrderOriginByOrdID( _elm( $lists, 'ORDER_ID' ) );
                $aLISTS_RESULT['lists'][$aKey]['ORDER_IDX']     = _elm( $orderOriginInfo, 'O_IDX' );
                $aLISTS_RESULT['lists'][$aKey]['aMemo']         = $this->orderModel->getLastMemo( _elm( $orderOriginInfo, 'O_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['aMemoCnt']      = $this->orderModel->getMemoCnt( _elm( $orderOriginInfo, 'O_IDX' ) );
            }
        }


        $statusArr      = [
            'request'=>[
                'ExchangeRequest',           //'교환 요청',
                'ExchangePickupRequest',     //'교환 수거 요청',
            ],
            'handle' =>[
                'ExchangeApproved',          //'교환 승인',
                'ExchangePending',           //'교환 보류',
            ],
            'processing' => [
                'ExchangeReturning',         //'교환 반송 중',
                'ExchangeReturnComplete',    //'교환 반송 완료',
                'ExchangeShippingProgress',  //'교환 재배송 중',
            ],
            'complate' => [
                'ExchangeShippingComplete',  //'교환 완료',
            ],
            'reject' => [
                'ExchangeRequestRetract',    //'교환 요청 철회',
                'ExchangeRejected',          //'교환 불가',
            ],
        ];


        $statusParam                                = [];
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'request' );
        $requestCnt                                 = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'handle' );
        $handleCnt                                 = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'processing' );
        $processingCnt                             = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'complate' );
        $complateCnt                            = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'reject' );
        $rejectCnt                          = $this->orderModel->getOrderInfoStatusCnt( $statusParam );



        $deliveryCompanys                           = [];
        $_deliveryCompanys                          = $this->orderModel->getDeliveryCompay();
        if( empty( $_deliveryCompanys ) == false ){
            foreach( $_deliveryCompanys as $dKey => $company ){
                $deliveryCompanys[_elm( $company, 'D_IDX' )]          = _elm( $company, 'D_COMPANY_NAME' );
            }
        }
        $deliverMain = current(
            array_column(
                array_filter($_deliveryCompanys, fn($company) =>
                    $company['D_FIX_YN'] === 'Y'
                ), 'D_IDX'
            )
        );

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $view_datas['deliveryCompanys']         = $deliveryCompanys;
            $view_datas['deliverMain']              = $deliverMain;




            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\order\Views\exchanges\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/order/orderLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;


        $response['requestCnt']                     = $requestCnt;
        $response['handleCnt']                      = $handleCnt;
        $response['processingCnt']                  = $processingCnt;
        $response['complateCnt']                    = $complateCnt;
        $response['rejectCnt']                      = $rejectCnt;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }


    #------------------------------------------------------------------
    # TODO: 반품 프로세스 리스트
    #------------------------------------------------------------------
    public function orderReturnProcessLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        if( empty(  _elm( $requests, 's_condition' ) ) === false &&  empty(  _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'orderNo' :
                    $modelParam['ORDID']            = _elm( $requests, 's_keyword' );
                    break;
                case 'memberId' :
                    $modelParam['MB_USERID']        = _elm( $requests, 's_keyword' );
                    break;
                case 'memberName' :
                    $modelParam['MB_NM']            = _elm( $requests, 's_keyword' );
                    break;
                case 'mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( _elm( $requests, 's_keyword' ) );
                    break;

            }
        }

        if( empty( _elm( $requests, 's_paymethod' ) ) === false ){
            $modelParam['O_PAY_METHOD']             = _elm( $requests, 's_paymethod' );
        }


        if ( empty(_elm($requests, 's_status') ) === true && !empty(_elm($requests, 's_ordStatus'))) {
            $modelParam['O_STATUS']                 = array_values(explode(',', _elm($requests, 's_ordStatus')));
        }else if( empty(_elm($requests, 's_status') ) === false ){
            $modelParam['O_STATUS']                 = _elm($requests, 's_status');
        }




        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['START_DATE']               = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['END_DATE']                 = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        $modelParam['order']                        = 'OO.O_ORDER_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->orderModel->getOrderInfoLists($modelParam);



        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){
            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                #------------------------------------------------------------------
                # TODO: 메모 마지막꺼만 가져오기
                #------------------------------------------------------------------
                $orderOriginInfo                                = $this->orderModel->getOrderOriginByOrdID( _elm( $lists, 'ORDER_ID' ) );
                $aLISTS_RESULT['lists'][$aKey]['ORDER_IDX']     = _elm( $orderOriginInfo, 'O_IDX' );
                $aLISTS_RESULT['lists'][$aKey]['aMemo']         = $this->orderModel->getLastMemo( _elm( $orderOriginInfo, 'O_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['aMemoCnt']      = $this->orderModel->getMemoCnt( _elm( $lists, 'ORDER_ID' ) );
            }
        }


        $statusArr      = [
            'request'     =>  [
                'ReturnRequest',                        //'반품 요청',
                'ReturnPickupRequest',                  //'반품 수거 요청',
            ],
            'handle'      => [
                'ReturnApproved',                       //'반품 승인',
                'ReturnPending',                        //'반품 보류',
            ],
            'processing'  => [
                'ReturnShippingProgress',               //'반품 반송 중',
            ],
            'complate'    => [
                'ReturnShippingComplete',               //'반품 반송 완료',
            ],
            'paycancel'   => [
                'ReturnCancelRequest',                  //'반품 결제 취소 예정',
                'ReturnCancelComplete',                 //'반품 결제 취소 완료',
            ],
            'refoundRequest' => [
                'ReturnRefundRequest',                  //'반품 환불 요청 대기',
                'ReturnRefundWaiting',                  //'반품 환불 예정',
                'ReturnRefundFailed',                   //'환불 재요청 대기',
            ],
            'refoundComplate'  => [
                'ReturnRefundComplete',                 //'반품 환불 완료',
            ],
            'reject'          => [
                'ReturnRequestRetract',                 //'반품 요청 철회',
                'ReturnRejected',                       //'반품 불가',
            ],
        ];


        $statusParam                                = [];
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'request' );
        $requestCnt                                 = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'handle' );
        $handleCnt                                 = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'processing' );
        $processingCnt                             = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'complate' );
        $complateCnt                            = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'paycancel' );
        $paycancelCnt                            = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'refoundRequest' );
        $refoundRequestCnt                            = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'refoundComplate' );
        $refoundComplateCnt                            = $this->orderModel->getOrderInfoStatusCnt( $statusParam );

        unset($statusParam['O_STATUS']);
        $statusParam['O_STATUS']                    = _elm( $statusArr, 'reject' );
        $rejectCnt                          = $this->orderModel->getOrderInfoStatusCnt( $statusParam );



        $deliveryCompanys                           = [];
        $_deliveryCompanys                          = $this->orderModel->getDeliveryCompay();
        if( empty( $_deliveryCompanys ) == false ){
            foreach( $_deliveryCompanys as $dKey => $company ){
                $deliveryCompanys[_elm( $company, 'D_IDX' )]          = _elm( $company, 'D_COMPANY_NAME' );
            }
        }
        $deliverMain = current(
            array_column(
                array_filter($_deliveryCompanys, fn($company) =>
                    $company['D_FIX_YN'] === 'Y'
                ), 'D_IDX'
            )
        );

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aOrderConf']               = $this->sharedConfig::$orderStatus;
            $view_datas['aPayMethodConf']           = $this->sharedConfig::$paymentMethods;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $view_datas['deliveryCompanys']         = $deliveryCompanys;
            $view_datas['deliverMain']              = $deliverMain;




            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\order\Views\returns\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/order/orderLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;


        $response['requestCnt']                     = $requestCnt;
        $response['handleCnt']                      = $handleCnt;
        $response['processingCnt']                  = $processingCnt;
        $response['complateCnt']                    = $complateCnt;
        $response['paycancelCnt']                   = $paycancelCnt;
        $response['refoundRequestCnt']              = $refoundRequestCnt;
        $response['refoundComplateCnt']             = $refoundComplateCnt;
        $response['rejectCnt']                      = $rejectCnt;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function getOrderShipTracking()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'ordIdx' => [
                'label'  => 'ordIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '주문 고유번호 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        $aData                                      = $this->orderModel->getOrderInfoByOrdIdx( _elm( $requests, 'ordIdx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 요청입니다.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['ordIdx']                       = _elm( $requests, 'ordIdx' );
        $view_datas['trackingInfo']                 = json_decode( _elm( $aData, 'O_SHIP_TRACKING' ), true );

        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\order\Views\order\_delivery_tracking' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }




    public function getMemoLists()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        #------------------------------------------------------------------
        # TODO: 데티어 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aOrderGoodsInfo                            = [];
        $orderOrogin                                = $this->orderModel->getOrderOriginByOrdIdx( _elm( $requests, 'ordIdx' ) );
        if( empty( $orderOrogin ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다.';

            return $this->respond( $response );
        }
        $orderInfo                                  = $this->orderModel->getOrderInfoByOrdID( _elm( $orderOrogin, 'O_ORDID' ) );
        if( empty( $orderInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다.';

            return $this->respond( $response );
        }

        $aConfig                                    = $this->sharedConfig::$orderStatus;

        foreach( $orderInfo as $oKey => $info ){
            $oData                                  = [];
            $orderGoodsInfo                         = $this->orderModel->getOrderGoodsByParentOdrIdx( _elm( $info, 'O_IDX' ) );
            $color                                  = $this->goodsModel->getGoodsColor( _elm( $orderGoodsInfo, 'P_PRID' ) );
            $goodsImg                               = $this->goodsModel->getGoodsInImagesFixSize( _elm( $orderGoodsInfo, 'P_PRID' ), '100' );
            $oData['ordIdPrd']                      = _elm( $info, 'O_ORDID' ).'-'.($oKey+1);
            $oData['infoIdx']                       = _elm( $info, 'O_IDX' );
            $oData['goodsImg']                      = _elm( $goodsImg, 'I_IMG_PATH' );
            $oData['goodsColor']                    = _elm( $color, 'G_COLOR' );
            $oData['productTitle']                  = _elm( $orderGoodsInfo, 'P_NAME' );
            $oData['productOptions']                = _elm( $orderGoodsInfo, 'P_OPTION_NAME' );
            $oData['orderCnt']                      = _elm( $orderGoodsInfo, 'P_NUM');
            $oData['orderStatus']                   = _elm( $info, 'O_STATUS' );
            $aOrderGoodsInfo[$oKey]                 = $oData;
        }

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['ordIdx']                       = _elm( $requests, 'ordIdx' );
        $view_datas['aOdrStatus']                   = $aConfig;
        $view_datas['orderGoodsInfo']               = $aOrderGoodsInfo;
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\order\Views\order\_memo_lists' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }

    public function getMemoListsRow()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();


        #------------------------------------------------------------------
        # TODO: 데티어 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aLists                                     = $this->orderModel->getMemoLists( _elm( $requests, 's_ordid' ) );
        $aMemoCnt                                   = $this->orderModel->getMemoCnt( _elm( $requests, 's_ordid' ) );
        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['lists']                        = $aLists;

        $aConfig                                    = $this->sharedConfig::$orderStatus;
        $view_datas['aOdrStatus']                   = $aConfig;
        $this->owensView->setViewDatas( $view_datas );
        $view_datas['M_ORDID']                      = _elm( $requests, 's_ordid' );
        $page_datas['rows']                         = view( '\Module\order\Views\order\_memo_row' , ['owensView' => $this->owensView] );
        $page_data['memoCnt']                       = $aMemoCnt;
        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }

    public function deleteMemo()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $modelParam                                 = [];
        $modelParam['M_IDX']                        = _elm( $requests, 'i_memoIdx' );
        $aData                                      = $this->orderModel->getMemoDataByIdx( _elm( $requests, 'i_memoIdx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다.';

            return $this->respond( $response );
        }

        $this->db->transBegin();

        $aStatus                                    = $this->orderModel->deleteMemo( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $this->db->transCommit();

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '주문 메모 삭제 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;


        return $this->respond( $response );
    }

    public function insertMemo()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $modelParam                                 = [];
        $modelParam['M_ORD_ID']                    = _elm( $requests, 'i_ordid' );

        $modelParam['M_CONTENT']                    = htmlspecialchars( nl2br( _elm( $requests, 'i_memo' ) ) );
        $modelParam['M_STATUS']                     = 'READY';
        $modelParam['M_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['M_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['M_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->db->transBegin();

        $aIdx                                       = $this->orderModel->insertMemo( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $this->db->transCommit();

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                               = [];
        $logParam['MB_HISTORY_CONTENT']         = '매모 저장 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;


        return $this->respond( $response );
    }



    public function changeMemoStatus()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_memoIdx' => [
                'label'  => 'i_memoIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'i_memoIdx 누락.',
                ],
            ],
            'i_status' => [
                'label'  => 'i_status',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'i_status 누락.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response);
        }

        $modelParam                                 = [];
        $modelParam['M_IDX']                        = _elm( $requests, 'i_memoIdx' );
        $modelParam['M_STATUS']                     = _elm( $requests, 'i_status' );

        $this->db->transBegin();
        $aStatus                                    = $this->orderModel->changeMemoStatus( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['messages']                   = '상태변경 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '주문 메모 상태변경 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();
        $response                                   = $this->_unset($response);
        $response['status']                         = 200;


        return $this->respond( $response );
    }


















    public function bannerRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$banner;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\design\Views\banner\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }
    public function bannerRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_title' => [
                'label'  => '팝업 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '배너 제목을 입력하세요.',
                ],
            ],
            'i_view_gbn' => [
                'label'  => '노출분류',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출분류를 선택하세요.',
                ],
            ],
            'i_locate' => [
                'label'  => '노출위치',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 위치를 선택하세요.',
                ],
            ],

            'i_period_start_date_time' => [
                'label'  => '노출 시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 시작일시를 입력하세요.',
                ],
            ],
            'i_period_end_date_time' => [
                'label'  => '노출 종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 종료일시를 입력하세요.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }
        #------------------------------------------------------------------
        # TODO: 파일 검사
        #------------------------------------------------------------------
        if( empty( $files ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '아이콘 파일을 선택하세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['B_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['B_VIEW_GBN']                   = _elm( $requests, 'i_view_gbn');
        $modelParam['B_STATUS']                     = _elm( $requests, 'i_status');
        $modelParam['B_PERIOD_START_AT']            = _elm( $requests, 'i_period_start_date_time');
        $modelParam['B_PERIOD_END_AT']              = _elm( $requests, 'i_period_end_date_time');
        $modelParam['B_LOCATE']                     = _elm( $requests, 'i_locate');
        $modelParam['B_LINK_URL']                   = _elm( $requests, 'i_link_url');
        $modelParam['B_OPEN_TARGET']                = _elm( $requests, 'i_open_target' );
        $modelParam['B_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['B_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['B_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'design/banner',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        foreach( $files as $file ){
            $file_return                            = $this->_upload( $file, $config );

            #------------------------------------------------------------------
            # TODO: 파일처리 실패 시
            #------------------------------------------------------------------
            if( _elm($file_return , 'status') === false ){
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = _elm( $file_return, 'error' );
                return $this->respond( $response, 400 );
            }

            #------------------------------------------------------------------
            # TODO: 데이터모델 세팅
            #------------------------------------------------------------------

            $modelParam['B_IMG_PATH']               = _elm( $file_return, 'uploaded_path');

        }


        $this->db->transBegin();
        $aIdx                                       = $this->bannerModel->insertBanner( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '팝업 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '저장 되었습니다.';

        return $this->respond( $response );
    }

    public function bannerDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'banner_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$banner;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aData                                      = $this->bannerModel->getBannerDataByIdx( _elm( $requests, 'banner_idx' ) );


        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\design\Views\banner\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function bannerDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_title' => [
                'label'  => '팝업 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '배너 제목을 입력하세요.',
                ],
            ],
            'i_view_gbn' => [
                'label'  => '노출분류',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출분류를 선택하세요.',
                ],
            ],
            'i_locate' => [
                'label'  => '노출위치',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 위치를 선택하세요.',
                ],
            ],

            'i_period_start_date_time' => [
                'label'  => '노출 시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 시작일시를 입력하세요.',
                ],
            ],
            'i_period_end_date_time' => [
                'label'  => '노출 종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '노출 종료일시를 입력하세요.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['B_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $this->bannerModel->getBannerDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $modelParam['B_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['B_VIEW_GBN']                   = _elm( $requests, 'i_view_gbn');
        $modelParam['B_STATUS']                     = _elm( $requests, 'i_status');
        $modelParam['B_PERIOD_START_AT']            = _elm( $requests, 'i_period_start_date_time');
        $modelParam['B_PERIOD_END_AT']              = _elm( $requests, 'i_period_end_date_time');
        $modelParam['B_LOCATE']                     = _elm( $requests, 'i_locate');
        $modelParam['B_LINK_URL']                   = _elm( $requests, 'i_link_url');
        $modelParam['B_OPEN_TARGET']                = _elm( $requests, 'i_open_target' );
        $modelParam['B_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['B_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['B_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------

        if( empty( $files ) === false ){

            $config                                     = [
                'path' => 'design/banner',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( $files as $file ){
                if( $file->getSize() > 0 ){
                    $file_return                        = $this->_upload( $file, $config );

                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']             = 400;
                        $response['alert']              = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------
                    $modelParam['B_IMG_PATH']           = _elm( $file_return, 'uploaded_path');

                    #------------------------------------------------------------------
                    # TODO: 기존 파일 삭제
                    #------------------------------------------------------------------
                    $finalFilePath                      = WRITEPATH . _elm( $aData, 'B_IMG_PATH' );
                    if (file_exists($finalFilePath)) {
                        @unlink($finalFilePath);
                    }
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->bannerModel->updateBanner( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '배너 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';

        return $this->respond( $response );
    }
    public function deleteBanner()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm($requests, 'i_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['B_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $this->bannerModel->getBannerDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->bannerModel->deleteBanner( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        $finalFilePath                              = WRITEPATH . _elm( $aData, 'B_IMG_PATH' );
        if (file_exists($finalFilePath)) {
            @unlink($finalFilePath);
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '배너 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }



        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제가 완료되었습니다.';


        return $this->respond( $response );

    }

    public function deleteBannerImg(){
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm($requests, 'i_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['P_IDX']                        = _elm( $requests, 'i_idx' );

        $aData                                      = $this->bannerModel->getBannerDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        $this->db->transBegin();
        $modelParam                                 = [];
        $modelParam['B_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['B_IMG_PATH']                   = '';

        $aStatus                                    = $this->bannerModel->deleteBannerImg( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        $finalFilePath                              = WRITEPATH . _elm( $aData, 'B_IMG_PATH' );
        if (file_exists($finalFilePath)) {
            @unlink($finalFilePath);
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '배너 이미지 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제가 완료되었습니다.';


        return $this->respond( $response );
    }

    public function changeOrderInfoStatus(){
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();



        print_r( $requests );



    }





















    public function changeOrderStatus()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_status' => [
                'label'  => 'i_status',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'i_status 값 누락.',
                ],
            ],
            'i_ordid' => [
                'label'  => 'i_ordid',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'i_ordid 값 누락.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response);
        }
        $orderOrigin                                = $this->orderModel->getOrderOriginByOrdID( _elm( $requests, 'i_ordid' ) );
        if( empty( $orderOrigin ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '주문정보가 없습니다.';

            return $this->respond( $response );
        }

        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: ORDER_INFO 주문 상태값 변경
        #------------------------------------------------------------------
        $infoParam                                  = [];
        $infoParam['O_STATUS']                      = _elm( $requests, 'i_status' );
        $infoParam['O_ORDID']                       = _elm( $requests, 'i_ordid' );

        $oInfoStatus                                = $this->orderModel->orderStatusChangeByOrdId( $infoParam );

        if ( $this->db->transStatus() === false || $oInfoStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['messages']                   = 'INFO 상태변경 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 1.origin 상태값 변경
        # TODO: 2. 입금전 취소이고  origin의 PAY_METHOD가 BankTransfer일 경우 재고 복구 상품
        # TODO: 2-1. 기존 상태가 PayComplete이상일 경우 - 회원 구매수량, 총 결제금액 차감.
        # TODO: 3. 입금확인( 결제완료 )  회원 구매수량, 총 결제금액 증가.
        #------------------------------------------------------------------
        $checkingStatus                             = [
            'PayWaiting', 'PayChecking', 'Created', 'PayCancelRequest', 'PayCancelComplete'
        ];
        #------------------------------------------------------------------
        # TODO: 취소처리
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_status' ) == 'PrePayCancelComplate' ){
            $messages                               = '입금전 취소처리';
            if( _elm( $orderOrigin, 'O_PAY_METHOD' ) == 'BankTransfer' ){
                $rollbackStatus                     = $this->rollbackOrderBenefits( _elm( $requests, 'i_ordid' ) );
                if( _elm($rollbackStatus, 'status') != 200 ){
                    $this->db->transRollback();
                    return $this->respond( $rollbackStatus );
                }
            }
            if( !in_array( _elm( $orderOrigin, 'O_STATUS' ) , $checkingStatus ) ){
                #------------------------------------------------------------------
                # TODO: 회원 결제 총 금액 삭감 업데이트
                #------------------------------------------------------------------
                $memberParam                        = [];
                $memberParam['MB_IDX']              = _elm( $orderOrigin, 'O_MB_IDX' );
                $memberParam['MB_SALES_CNT']        = 1;
                //2025-02-21 홍사억 팀장 문의 후 전체 상품금액으로 결정
                $memberParam['MB_SALES_AMT']        = _elm( $orderOrigin, 'O_PG_PRICE' );

                $mStatus                            = $this->memberModel->unsetPurchaseAmt( $memberParam );
                if ( $this->db->transStatus() === false || $mStatus === false ) {
                    $this->db->transRollback();
                    $response['status']                     = 400;
                    $response['messages']                   = '회원 정보 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }

            }

        }else if( _elm( $requests, 'i_status' ) == 'PayComplete' ){
            $messages                               = '입금확인처리';
            if( in_array( _elm( $orderOrigin, 'O_STATUS' ) , $checkingStatus ) ){
                #------------------------------------------------------------------
                # TODO: 회원 결제 총 금액 증가 업데이트
                #------------------------------------------------------------------
                $memberParam                        = [];
                $memberParam['MB_IDX']              = _elm( $orderOrigin, 'O_MB_IDX' );
                $memberParam['MB_SALES_CNT']        = 1;
                //2025-02-21 홍사억 팀장 문의 후 전체 상품금액으로 결정
                $memberParam['MB_SALES_AMT']        = _elm( $orderOrigin, 'O_PG_PRICE' );

                $mStatus                            = $this->memberModel->setPurchaseAmt( $memberParam );
                if ( $this->db->transStatus() === false || $mStatus === false ) {
                    $this->db->transRollback();
                    $response['status']                     = 400;
                    $response['messages']                   = '회원 정보 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상테변경 ( '.$messages.' ) - orderId :'._elm( $requests, 'i_ordid' ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();
        $response['status']                         = 200;
        $response['alert']                          = $messages.' 가 완료되었습니다.';


        return $this->respond( $response );
    }

    public function rollbackOrderBenefits( $ordId )
    {
        if( empty( $ordId ) === true ){
            return ['status' => '400', 'messages'=> '주문번호 누락'];
        }

        $orderData                                  = $this->orderModel->getOrderOriginByOrdId( $ordId );

        $memberInfo                                 = $this->memberModel->getMemberInfoByIdx( _elm( $orderData, 'O_MB_IDX' ) );

        if( _elm( $orderData, 'O_CPN_PLUS' ) != '[]' ){
            $cpnInfo                                = json_decode( _elm( $orderData, 'O_CPN_PLUS' ), true );
            if( empty($cpnInfo) === false ){
                foreach( $cpnInfo as $cpnIdx){
                    $aCpnIsInfo                     = $this->couponModel->getCouponIssueDataByIdx( $cpnIdx );
                    if( _elm( $orderData, 'O_MB_IDX' ) == _elm( $aCpnIsInfo, 'I_MB_IDX' ) ){
                        if( _elm( $aCpnIsInfo, 'I_STATUS' ) !== 'Y' ){
                            $cpnParam                       = [];
                            $cpnParam['I_IDX']              = $cpnIdx;
                            $cpnParam['I_STATUS']           = 'N';
                            $cpnParam['I_RECOVERY_AT']      = date('Y-m-d H:i:s');
                            $cpnParam['I_RECOVERY_REASON']  = _elm( $aCpnIsInfo, 'I_RECOVERY_REASON' ).PHP_EOL.'//일시:'.date('Y-m-d H:i:s')."::".'결제 취소 복구';
                            $cStatus                        = $this->couponModel->returnCpnIssueStatus( $cpnParam );
                            if ( $this->db->transStatus() === false || $cStatus === false ) {
                                $this->db->transRollback();
                                $response['status']         = 400;
                                $response['messages']       = '쿠폰 복원 중 에러발생';
                                return $response ;
                            }
                        }
                    }
                }
            }
        }


        $_userHavePoint                             = $this->memberModel->userPointSummery( _elm( $orderData, 'O_MB_IDX' ) );
        $ADD_MILEAGE                                = _elm( $_userHavePoint, 'ADD_MILEAGE', 0 );
        $USE_MILEAGE                                = _elm( $_userHavePoint, 'USE_MILEAGE', 0 );
        $DED_MILEAGE                                = _elm( $_userHavePoint, 'DED_MILEAGE', 0 );
        $EXP_MILEAGE                                = _elm( $_userHavePoint, 'EXP_MILEAGE', 0 );

        $userHavePoint                              = $ADD_MILEAGE - ( $USE_MILEAGE + $DED_MILEAGE + $EXP_MILEAGE);
        #------------------------------------------------------------------
        # TODO: 포인트 복원
        #------------------------------------------------------------------
        if( empty( _elm($orderData, 'O_USE_MILEAGE') ) === false ){
            $usePointParam                          = [];
            $usePointParam['M_MB_IDX']              = _elm( $orderData, 'O_MB_IDX' );
            $usePointParam['M_TYPE']                = 'o';
            $usePointParam['M_TYPE_CD']             = _elm($orderData, 'O_ORDID');
            $usePointParam['M_GBN']                 = 'add';
            $usePointParam['M_BEFORE_MILEAGE']      = $userHavePoint;
            $usePointParam['M_AFTER_MILEAGE']       = $userHavePoint + (int)_elm( $orderData, 'O_USE_MILEAGE' ) ;
            $usePointParam['M_MILEAGE']             = (int)_elm( $orderData, 'O_USE_MILEAGE' );
            $usePointParam['M_REASON_CD']           = '122';
            $usePointParam['M_REASON_MSG']          = '결제 취소 복원';
            $usePointParam['M_CREATE_AT']           = date('Y-m-d H:i:s');
            $usePointParam['M_CREATE_IP']           = $this->request->getIPAddress();

            $pStauts                                = $this->memberModel->insertUserPointHistory( $usePointParam );

            if ( $this->db->transStatus() === false || $pStauts === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['messages']               = '포인트 복원 등록 중 에러발생';
                return $response;
            }


            #------------------------------------------------------------------
            # TODO: 포인트 summery 업데이트
            #------------------------------------------------------------------
            $pSummeryParam                          = [];
            $pSummeryParam['S_MB_IDX']              = _elm( $orderData, 'O_MB_IDX' ) ;
            $pSummeryParam['ADD_MILEAGE']           = _elm( $_userHavePoint, 'ADD_MILEAGE', 0 )+ (int)_elm( $orderData, 'O_USE_MILEAGE' );
            $pSummeryParam['USE_MILEAGE']           = _elm( $_userHavePoint, 'USE_MILEAGE', 0 );
            $pSummeryParam['DED_MILEAGE']           = _elm( $_userHavePoint, 'DED_MILEAGE', 0 );
            $pSummeryParam['EXP_MILEAGE']           = _elm( $_userHavePoint, 'EXP_MILEAGE', 0 );
            $pSummeryParam['LAST_UPDATE_AT']        = date('Y-m-d H:i:s');
            $pSummeryParam['LAST_UPDATE_IP']        = $this->request->getIPAddress();
            $pSummeryParam['LAST_MB_IDX]']          = _elm( $orderData, 'O_MB_IDX' ) ;
            $psStauts                               = $this->memberModel->updateUserSummeryPoint( $pSummeryParam );

            if ( $this->db->transStatus() === false || $pStauts === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['messages']               = '포인트 summery 복원 등록 중 에러발생';
                return $response ;
            }
        }
        $response['status']                         = 200;
        $response['messages']                       = 'success';
        return $response;
    }
}

