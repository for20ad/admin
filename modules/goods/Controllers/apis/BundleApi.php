<?php
#------------------------------------------------------------------
# BrandApi.php
# 브랜드관리 Api
# 김우진
# 2024-08-16 14:59:18
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;


use Module\goods\Models\BundleModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class BundleApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $aConfig;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->aConfig                              = new goodsConfig();
        $this->aConfig                              = $this->aConfig->goods;
    }

    public function defaultGoodsRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                 = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }



        $modelParam                                 = [];

        $aData                                      = $bundleModel->getDefaultGoodsLists();

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'A_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteDefaultGoods( $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'A_GOODS_IDX' => $goodsIdx,
                    'A_SORT' => $index + 1,
                    'A_LIMIT' => _elm( $requests, 'i_limit_cnt' ) ,
                    'A_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'A_UPDATE_IP' => $this->request->getIPAddress(),
                    'A_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['A_GOODS_IDX']      = $goodsIdx;
                    $aStatus                        = $bundleModel->updateDefaultGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['A_CREATE_AT']      = date( 'Y-m-d H:i:s' );
                    $modelParam['A_CREATE_IP']      = $this->request->getIPAddress();
                    $modelParam['A_CREATE_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertDefaultGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '기본상품상품 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );


    }
    public function bestGoodsRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                 = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }



        $modelParam                                 = [];

        $aData                                      = $bundleModel->getBestGoodsLists();

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'A_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteBestGoods( $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'A_GOODS_IDX' => $goodsIdx,
                    'A_SORT' => $index + 1,
                    'A_LIMIT' => _elm( $requests, 'i_limit_cnt' ) ,
                    'A_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'A_UPDATE_IP' => $this->request->getIPAddress(),
                    'A_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['A_GOODS_IDX']      = $goodsIdx;
                    $aStatus                        = $bundleModel->updateBestGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['A_CREATE_AT']      = date( 'Y-m-d H:i:s' );
                    $modelParam['A_CREATE_IP']      = $this->request->getIPAddress();
                    $modelParam['A_CREATE_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertBestGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '베스트상품 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );


    }

    public function newGoodsRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                 = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }



        $modelParam                                 = [];

        $aData                                      = $bundleModel->getNewGoodsLists();

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'A_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteNewGoods( $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'A_GOODS_IDX' => $goodsIdx,
                    'A_SORT' => $index + 1,
                    'A_LIMIT' => _elm( $requests, 'i_limit_cnt' ) ,
                    'A_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'A_UPDATE_IP' => $this->request->getIPAddress(),
                    'A_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['A_GOODS_IDX']      = $goodsIdx;
                    $aStatus                        = $bundleModel->updateNewGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['A_CREATE_AT']      = date( 'Y-m-d H:i:s' );
                    $modelParam['A_CREATE_IP']      = $this->request->getIPAddress();
                    $modelParam['A_CREATE_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertNewGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '최신상품 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );


    }

    public function getTimeSaleLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());


        if (empty( _elm($param, 'post') ) === false)
        {
            $requests                               = _elm($param, 'post');
        }

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
        # TODO:  리스트 가져오기
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_TITLE']                      = _elm( $requests, 's_title' );
        $modelParam['START_DATE']                   = _elm( $requests, 's_start_date' );
        $modelParam['END_DATE']                     = _elm( $requests, 's_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 's_status' );


        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' A_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' A_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' A_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' A_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' A_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $bundleModel->getTimeSaleLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );


            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\goods\Views\bundle\timeSale\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/timeSale' ), '/');
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

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function timeSaleDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'i_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $bundleModel->timeSalseDataByIdx( _elm( $requests, 'i_idx' ) );


        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '폼데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );


        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\timeSale\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }


    public function timeSaleDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idx' => [
                'label'  => '키값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '키값이 누락되었습니다. 새고침 후 이용해주세요.',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
                ],
            ],
            'i_start_date' => [
                'label'  => '시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '시작일시를 선택하세요.',
                ],
            ],
            'i_end_date' => [
                'label'  => '종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '종료일시를 선택하세요.',
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
        $aData                                      = $bundleModel->timeSalseDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 기간체크하여 중복 또는 겹치는 기간이 있는지 체크하여 리턴
        #------------------------------------------------------------------
        if( date( 'Y-m-d H:i', strtotime(_elm( $aData, 'A_PERIOD_START_AT' ) ) ) != _elm( $requests, 'i_start_date' ) ||
            date( 'Y-m-d H:i', strtotime(_elm( $aData, 'A_PERIOD_END_AT' ) ) ) != _elm( $requests, 'i_end_date' ) ){
            $aDiffStatus                                = $bundleModel->timeSaleDiffTimes( $modelParam );
            if( $aDiffStatus === false ){
                $response['status']                     = 400;
                $response['alert']                      = '기간을 확인해주세요.';

                return $this->respond( $response );
            }
        }


        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->updateTimeSale( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '타임세일 데이터 수정 - org_data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ). "// newData:".json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function timeSaleRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\timeSale\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function timeSaleRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
                ],
            ],
            'i_start_date' => [
                'label'  => '시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '시작일시를 선택하세요.',
                ],
            ],
            'i_end_date' => [
                'label'  => '종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '종료일시를 선택하세요.',
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
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 기간체크하여 중복 또는 겹치는 기간이 있는지 체크하여 리턴
        #------------------------------------------------------------------
        $aDiffStatus                                = $bundleModel->timeSaleDiffTimes( $modelParam );
        if( $aDiffStatus === false ){
            $response['status']                     = 400;
            $response['alert']                      = '기간을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->insertTimeSale( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '타임세일 데이터 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
    public function deleteTimeSale()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idx' => [
                'label'  => '키값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '키값이 누락되었습니다. 새고침 후 이용해주세요.',
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
        $aData                                      = $bundleModel->timeSalseDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 상위 데이터 삭제
        #------------------------------------------------------------------
        $aStatus                                    = $bundleModel->deleteTimeSale( _elm( $requests, 'i_idx' ) );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상위데이터 삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 하위 데이터 삭제
        #------------------------------------------------------------------
        $bStatus                                    = $bundleModel->deleteTimeSaleDetail( _elm( $requests, 'i_idx' ) );

        if ( $this->db->transStatus() === false || $bStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '하위데이터 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '타임세일 데이터 삭제 - data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );
    }

    public function timeSaleDetailRegister( $param= [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_p_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX깂 누락. 다시 시도해주세요.',
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

        $aData                                      = $bundleModel->getTimeSaleGoodsLists( _elm( $requests, 'i_p_idx' ) );

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'AD_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteTimeSaleDetailGoods( _elm( $requests, 'i_p_idx' ), $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'AD_GOODS_IDX' => $goodsIdx,
                    'AD_SORT' => $index + 1,
                    'AD_P_IDX' => _elm( $requests, 'i_p_idx' ) ,
                    'AD_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'AD_UPDATE_IP' => $this->request->getIPAddress(),
                    'AD_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['AD_GOODS_IDX']     = $goodsIdx;
                    $aStatus                        = $bundleModel->updateTimeSaleDetailGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['AD_CREATE_AT']     = date( 'Y-m-d H:i:s' );
                    $modelParam['AD_CREATE_IP']     = $this->request->getIPAddress();
                    $modelParam['AD_CREATE_MB_IDX'] = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertTimeSaleDetailGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '타임세일 상품 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );

    }


    public function getWeeklyGoodsLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());


        if (empty( _elm($param, 'post') ) === false)
        {
            $requests                               = _elm($param, 'post');
        }

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
        # TODO:  리스트 가져오기
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_TITLE']                      = _elm( $requests, 's_title' );
        $modelParam['START_DATE']                   = _elm( $requests, 's_start_date' );
        $modelParam['END_DATE']                     = _elm( $requests, 's_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 's_status' );


        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' A_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' A_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' A_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' A_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' A_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $bundleModel->getWeeklyGoodsLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );


            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\goods\Views\bundle\weekly\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/weeklyGoods' ), '/');
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

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function weeklyGoodsRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\weekly\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function weeklyGoodsRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
                ],
            ],
            'i_start_date' => [
                'label'  => '시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '시작일시를 선택하세요.',
                ],
            ],
            'i_end_date' => [
                'label'  => '종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '종료일시를 선택하세요.',
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
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 기간체크하여 중복 또는 겹치는 기간이 있는지 체크하여 리턴
        #------------------------------------------------------------------
        $aDiffStatus                                = $bundleModel->weeklyGoodsDiffTimes( $modelParam );
        if( $aDiffStatus === false ){
            $response['status']                     = 400;
            $response['alert']                      = '기간을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->insertWeeklyGoods( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '주간상품 데이터 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function weeklyGoodsDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'i_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $bundleModel->weeklyGoodsDataByIdx( _elm( $requests, 'i_idx' ) );


        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '폼데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );


        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\weekly\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }


    public function weeklyGoodsDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idx' => [
                'label'  => '키값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '키값이 누락되었습니다. 새고침 후 이용해주세요.',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
                ],
            ],
            'i_start_date' => [
                'label'  => '시작일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '시작일시를 선택하세요.',
                ],
            ],
            'i_end_date' => [
                'label'  => '종료일시',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '종료일시를 선택하세요.',
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
        $aData                                      = $bundleModel->weeklyGoodsDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 기간체크하여 중복 또는 겹치는 기간이 있는지 체크하여 리턴
        #------------------------------------------------------------------
        if( date( 'Y-m-d', strtotime(_elm( $aData, 'A_PERIOD_START_AT' ) ) ) != _elm( $requests, 'i_start_date' ) ||
            date( 'Y-m-d', strtotime(_elm( $aData, 'A_PERIOD_END_AT' ) ) ) != _elm( $requests, 'i_end_date' ) ){
            $aDiffStatus                                = $bundleModel->weeklyGoodsDiffTimes( $modelParam );
            if( $aDiffStatus === false ){
                $response['status']                     = 400;
                $response['alert']                      = '기간을 확인해주세요.';

                return $this->respond( $response );
            }
        }


        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->updateWeeklyGoods( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '주간상품 데이터 수정 - org_data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ). "// newData:".json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function weeklyGoodsDetailRegister( $param= [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_p_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX깂 누락. 다시 시도해주세요.',
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

        $aData                                      = $bundleModel->getWeeklyGoodsDetailLists( _elm( $requests, 'i_p_idx' ) );

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'AD_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteWeeklyGoodsDetailGoods( _elm( $requests, 'i_p_idx' ), $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'AD_GOODS_IDX' => $goodsIdx,
                    'AD_SORT' => $index + 1,
                    'AD_P_IDX' => _elm( $requests, 'i_p_idx' ) ,
                    'AD_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'AD_UPDATE_IP' => $this->request->getIPAddress(),
                    'AD_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['AD_GOODS_IDX']     = $goodsIdx;
                    $aStatus                        = $bundleModel->updateWeeklyGoodsDetailGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['AD_CREATE_AT']     = date( 'Y-m-d H:i:s' );
                    $modelParam['AD_CREATE_IP']     = $this->request->getIPAddress();
                    $modelParam['AD_CREATE_MB_IDX'] = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertWeeklyGoodsDetailGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '주간상품 상품리스트 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );

    }
    public function deleteWeeklyGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idx' => [
                'label'  => '키값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '키값이 누락되었습니다. 새고침 후 이용해주세요.',
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
        $aData                                      = $bundleModel->weeklyGoodsDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 상위 데이터 삭제
        #------------------------------------------------------------------
        $aStatus                                    = $bundleModel->deleteWeeklyGoods( _elm( $requests, 'i_idx' ) );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상위데이터 삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 하위 데이터 삭제
        #------------------------------------------------------------------
        $bStatus                                    = $bundleModel->deleteWeeklyGoodsDetail( _elm( $requests, 'i_idx' ) );

        if ( $this->db->transStatus() === false || $bStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '하위데이터 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '주간상품 데이터 삭제 - data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );
    }




    public function getHotKeywordLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());


        if (empty( _elm($param, 'post') ) === false)
        {
            $requests                               = _elm($param, 'post');
        }

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
        # TODO:  리스트 가져오기
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_TITLE']                      = _elm( $requests, 's_title' );
        $modelParam['START_DATE']                   = _elm( $requests, 's_start_date' );
        $modelParam['END_DATE']                     = _elm( $requests, 's_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 's_status' );


        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' A_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' A_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' A_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' A_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' A_SORT ASC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $bundleModel->getHotKeywordLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );


            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\goods\Views\bundle\hotKeyword\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/hotKeyword' ), '/');
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

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function hotKeywordRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\hotKeyword\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function hotKeywordRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
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

        $_sort                                      = $bundleModel->getHotkeywordSortMax();
        $modelParam                                 = [];
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_SORT']                       = _elm( $_sort, 'max' ) + 1;
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->insertHotKeyword( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '핫키워드 데이터 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function hotKeywordDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'i_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $bundleModel->hotKeywordDataByIdx( _elm( $requests, 'i_idx' ) );


        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '폼데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );


        $page_datas['detail']                       = view( '\Module\goods\Views\bundle\hotKeyword\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function updateHotKeywordSort()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $bundleModel                                = new BundleModel();


        $this->db->transBegin();

        foreach( _elm( $requests, 'sort' ) as $sort => $aidx ){
            $modelParam                             = [];
            $modelParam['A_SORT']                   = $sort + 1;
            $modelParam['A_IDX']                    = $aidx;

            $aStatus                                = $bundleModel->setHotKeywordSort( $modelParam );

            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '';

        return $this->respond( $response );
    }

    public function hotKeywordDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $bundleModel                                = new BundleModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idx' => [
                'label'  => '키값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '키값이 누락되었습니다. 새고침 후 이용해주세요.',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_limit_cnt' => [
                'label'  => '메인페이지 노출수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메인페이지 노출수를 입력하세요.',
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
        $aData                                      = $bundleModel->hotKeywordDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['A_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['A_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['A_LIMIT']                      = _elm( $requests, 'i_limit_cnt' );
        $modelParam['A_PERIOD_START_AT']            = _elm( $requests, 'i_start_date' );
        $modelParam['A_PERIOD_END_AT']              = _elm( $requests, 'i_end_date' );
        $modelParam['A_OPEN_STATUS']                = _elm( $requests, 'i_status' );
        $modelParam['A_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['A_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['A_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );


        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $bundleModel->updateHotKeyword( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '핫키워드 데이터 수정 - org_data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ). "// newData:".json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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


    public function hotKeywordDetailRegister( $param= [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_p_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX깂 누락. 다시 시도해주세요.',
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

        $aData                                      = $bundleModel->getHotKeywordDetailLists( _elm( $requests, 'i_p_idx' ) );

        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'AD_GOODS_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_goods_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteHotKeywordDetailGoods( _elm( $requests, 'i_p_idx' ), $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_goods_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_goods_idxs' ) as $index => $goodsIdx) {
                $modelParam                         = [
                    'AD_GOODS_IDX' => $goodsIdx,
                    'AD_SORT' => $index + 1,
                    'AD_P_IDX' => _elm( $requests, 'i_p_idx' ) ,
                    'AD_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'AD_UPDATE_IP' => $this->request->getIPAddress(),
                    'AD_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];



                if (in_array($goodsIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['AD_GOODS_IDX']     = $goodsIdx;
                    $aStatus                        = $bundleModel->updateHotKeywordDetailGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['AD_CREATE_AT']     = date( 'Y-m-d H:i:s' );
                    $modelParam['AD_CREATE_IP']     = $this->request->getIPAddress();
                    $modelParam['AD_CREATE_MB_IDX'] = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertHotKeywordDetailGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '핫키워드 상품리스트 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );

    }


    public function hotBrandRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $bundleModel                                = new BundleModel();
        $requests                                   = _trim($this->request->getPost());


        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $modelParam                                 = [];

        $aData                                      = $bundleModel->getHotBrandLists();


        $this->db->transBegin();



        $existingIds                                = [];

        if( empty( $aData ) === false ){
            $existingIds                            = array_column( $aData, 'A_BRAND_IDX' );
            #------------------------------------------------------------------
            # TODO: 새 배열에 없는 항목들은 삭제.
            #------------------------------------------------------------------
            $itemsToDelete = array_diff($existingIds, _elm( $requests, 'i_group_brand_idxs' ) );
            if (!empty($itemsToDelete)) {
                $dStatus                            = $bundleModel->deleteHotBrandGoods( $itemsToDelete );
                if ( $this->db->transStatus() === false || $dStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 상품 선 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        if( empty( _elm( $requests, 'i_group_brand_idxs' ) ) === false ){
            foreach ( _elm( $requests, 'i_group_brand_idxs' ) as $index => $brandIdx) {
                $modelParam                         = [
                    'A_BRAND_IDX' => $brandIdx,
                    'A_SORT' => $index + 1,
                    'A_LIMIT' => _elm( $requests, 'i_limit_cnt', 10, true ) ,
                    'A_UPDATE_AT' => date('Y-m-d H:i:s'),
                    'A_UPDATE_IP' => $this->request->getIPAddress(),
                    'A_UPDATE_MB_IDX' => _elm( $this->session->get('_memberInfo') , 'member_idx' ),
                ];

                if (in_array($brandIdx, $existingIds)) {
                    // 업데이트
                    $modelParam['A_BRAND_IDX']      = $brandIdx;
                    $aStatus                        = $bundleModel->updateHotBrandGoods( $modelParam );
                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    // 삽입
                    $modelParam['A_CREATE_AT']      = date( 'Y-m-d H:i:s' );
                    $modelParam['A_CREATE_IP']      = $this->request->getIPAddress();
                    $modelParam['A_CREATE_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $aStatus                        = $bundleModel->insertHotBrandGoods($modelParam);

                    if ( $this->db->transStatus() === false || $aStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '상품 추가 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '핫브랜드 저장 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );


    }
}