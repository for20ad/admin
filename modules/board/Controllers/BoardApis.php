<?php

namespace Module\board\Controllers;

use Module\core\Controllers\ApiController;

use Module\board\Models\BoardModel;

use Module\membership\Models\MembershipModel;
use Module\order\Models\OrderModel;

use Module\goods\Models\GoodsModel;
use Module\goods\Models\IconsModel;
use Module\goods\Models\BrandModel;

use App\Libraries\MemberLib;
use App\Libraries\OwensView;
use App\Libraries\KakaoLib;

use Exception;

class BoardApis extends ApiController
{
    protected $db, $boardModel, $memberModel, $memberlib, $owensView;
    protected $orderModel, $iconsModel, $brandModel, $goodsModel;
    public function __construct()
    {
        parent::__construct();
        $this->db                                   = \Config\Database::connect();
        $this->memberModel                          = new MembershipModel();
        $this->boardModel                           = new BoardModel();
        $this->memberlib                            = new MemberLib();
        $this->owensView                            = new OwensView();
        $this->orderModel                           = new OrderModel();
        $this->iconsModel                           = new IconsModel();
        $this->brandModel                           = new BrandModel();
        $this->goodsModel                           = new GoodsModel();
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
}