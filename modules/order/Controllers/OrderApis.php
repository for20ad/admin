<?php

namespace Module\order\Controllers;

use Module\core\Controllers\ApiController;

use Module\board\Models\BoardModel;

use Module\membership\Models\MembershipModel;
use Module\order\Models\OrderModel;
use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\IconsModel;
use Module\goods\Models\BrandModel;
use Module\goods\Models\GoodsAddInfoModel;


use App\Libraries\MemberLib;
use App\Libraries\OwensView;
use App\Libraries\KakaoLib;

use App\Libraries\LogisCrawling;

use Exception;

class OrderApis extends ApiController
{
    protected $db, $boardModel, $memberModel, $memberlib, $owensView;
    protected $goodsModel, $categoryModel, $iconsModel, $brandModel, $goodsAddInfoModel;
    protected $orderModel;
    public function __construct()
    {
        parent::__construct();
        $this->db                                   = \Config\Database::connect();
        $this->memberModel                          = new MembershipModel();
        $this->boardModel                           = new BoardModel();

        $this->goodsModel                           = new GoodsModel();
        $this->categoryModel                        = new CategoryModel();
        $this->iconsModel                           = new IconsModel();
        $this->brandModel                           = new BrandModel();
        $this->goodsAddInfoModel                    = new GoodsAddInfoModel();

        $this->memberlib                            = new MemberLib();
        $this->owensView                            = new OwensView();
        $this->orderModel                           = new OrderModel();
    }
    public function pushSms( $param )
    {
        #------------------------------------------------------------------
        # TODO: 알림톡 데이터 세팅
        #------------------------------------------------------------------
        $config = $this->sharedConfig::$talk;

        $KakaoInfo                                  = _elm( $config, 'KakaoInfo' );
        $kakaoTemplateInfo                          = _elm( $config, 'kakaoTemplate' );

        $kakao                                      = new KakaoLib;
        $talkParam                                  = [];
        $template_code                              = array_search( _elm( $param, 'temp_name' ), $kakaoTemplateInfo);
        if( empty( $template_code ) === true ){
            $returnData['status']                   = 200;
            $returnData['error']                    = '템플릿 데이터가 없습니다.';
            return $returnData;
        }

        $kakao_param                                = [
            'MB_IDX'                                => _elm( $param, 'MB_IDX' ),
            'TEMPLATE_CODE'                         => $template_code,
            'YELLOWID_KEY'                          => $KakaoInfo['YELLOWID_KEY'],
            'FCM_SEND'                              => _elm( $param, 'FCM_SEND' ),
            'CALLPHONE'                             => _elm($param, 'mobile_num'),
            'REQTIME'                               => '00000000000000',
            //'AGENT_FLAG'    => rand(0,4),
            'typeValues'                            => _elm( $param, 'fields' ),

        ];

        try {
            $kakao->sendATalk($kakao_param);

            $returnData                             = [];
            $returnData['status']                   = 200;
            $returnData['auth_num']                 = $auth_num;
            return $returnData;

        }
        //알림톡발송 임시 주석처리
        catch(\Exception $e) {
            $response['status']                     = '400';
            $response['alert']                      = $e->getMessage();

            return $this->fail( $e->getMessage(), 401, '303' );
        }

    }
    public function trackingLoginsData( $shipNum )
    {
        $logis                                      = new LogisCrawling();

        $aData = $logis->logenLogis2( '91175140346' );

        return $aData;
    }

    #------------------------------------------------------------------
    # TODO: 본인인증 예제
    #------------------------------------------------------------------

    public function verification()
    {
        $pageDatas                                  = [];
        //$requests                                   = $this->request->getPost();
        $requests                                   = $this->request->getJSON(true);

        print_r( $requests );
        $_impConfig                                 = $this->sharedConfig::$portOne;
        $impConfig                                  = _elm( $_impConfig, 'certification' );
        $imp_key                                    = _elm( $impConfig, 'restApiKey' );
        $imp_secret                                 = _elm( $impConfig, 'certKey' );

        $client                                     = \Config\Services::curlrequest();
        #------------------------------------------------------------------
        # TODO: api 토큰 발급
        #------------------------------------------------------------------
        $getToken                                   = $client->post( 'https://api.portone.io/login/api-secret' , [
            'headers' => [
                'Content-Type'                      => 'application/json',
            ],
            'json' =>[
                'apiSecret'                         => $imp_secret,
            ],

        ]);
        $aTokenInfo                                 = json_decode($getToken->getBody(), true);

        if( _elm( $aTokenInfo, 'code' ) == 0 ){
            $token                                  =_elm( $aTokenInfo, 'accessToken' );

            $authInfo                               = $client->get('https://api.portone.io/identity-verifications/'.(_elm( $requests, 'identityVerificationId' )), [
                'headers' => [
                    'Content-Type'                  => 'application/json',
                    'Authorization'                 => 'Bearer '.$token,
                ],
            ]);

            if ($authInfo->getStatusCode() === 200) {
                $authAllData                        = json_decode($authInfo->getBody(), true);
                $authData                           = _elm( $authAllData, 'verifiedCustomer' );


                $cMember                            = $this->memberModel->getMemberInfoByCi( _elm($authData, 'ci' ) );
                if( empty( $cMember ) === false ){
                    $response['status']             = 400;
                    $response['error']              = 400;
                    $response['messages']           = '이미 가입된 회원입니다.';

                    return $this->respond( $response );
                }

                $findParam                          = [];
                $findParam['MB_NM']                 = _elm( $authData, 'name' );
                $findParam['MB_MOBILE_NUM']         = $this->_aesEncrypt( _elm( $authData, 'phoneNumber' ) );
                $findParam['MB_BIRTH']              = _elm( $authData, 'birthDate' );
                $aFindResult                        = $this->memberModel->findMemeberData( $findParam );

                if( $aFindResult == null ){
                    $response['status']             = 400;
                    $response['error']              = 400;
                    $response['messages']           = '잘못된 회원 데이터입니다.';

                    return $this->respond( $response );
                }else{
                    #------------------------------------------------------------------
                    # TODO: 회원 가입된 상태
                    #------------------------------------------------------------------
                    if( empty( $aFindResult ) === false ){
                        $response['status']         = 400;
                        $response['error']          = 400;
                        $response['messages']       = '이미 가입된 회원입니다.';

                        return $this->respond( $response );
                    }
                    #------------------------------------------------------------------
                    # TODO: 신규가입 가능
                    #------------------------------------------------------------------
                    else{
                        $crypto                     = new \App\Helpers\CryptoHelper();
                        // 데이터 암호화

                        $returnData                 = [];
                        $returnData['MB_NM']        = _elm( $authData, 'name' );
                        $returnData['MB_MOBILE_NUM']= $crypto->encrypt( _elm( $authData, 'phoneNumber' ) );
                        $returnData['MB_BIRTH']     = _elm( $authData, 'birthDate' );

                        $response['status']         = 200;
                        $response['data']           = $returnData;
                    }
                }

            } else {
                $response['status']         = 400;
                $response['error']          = 400;
                $response['messages']       = $authInfo->getReasonPhrase();

                return $this->respond( $response );
            }

        }else{
            $response['status']                     = 400;
            $response['alert']                      = '토큰발급 실패';

            return $this->respond( $response );
        }


        return $this->respond( $response );


    }
}