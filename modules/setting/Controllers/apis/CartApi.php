<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\CartModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class CartApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->memberlib                           = new MemberLib();
        $this->db                                  = \Config\Database::connect();
    }

    public function setData()
    {
        $response                                  = $this->_initResponse();
        $requests                                  = $this->request->getPost();
        $cartModel                                 = new CartModel();

        $aData                                     = $cartModel->getCartSetting();

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                = [];
        $modelParam['C_SAVE_PERIOD']               = _elm( $requests, 'i_save_period_check' ) == 'Y' ? _elm( $requests, 'i_save_period' ) : 0 ;
        $modelParam['C_SAVE_CNT']                  = _elm( $requests, 'i_save_cnt' );
        $modelParam['C_REL_GOODS_USE_YN']          = _elm( $requests, 'i_rel_goods_use_yn' );
        $modelParam['C_REL_GOODS_CNT']             = _elm( $requests, 'i_rel_goods_check' ) == 'Y' ?  _elm( $requests, 'i_rel_goods_cnt' ) : 0 ;
        $modelParam['C_RECENT_PERIOD']             = _elm( $requests, 'i_recent_period_check' ) == 'Y' ? _elm( $requests, 'i_recent_period' ) : 0 ;
        $modelParam['C_RECENT_CNT']                = _elm( $requests, 'i_recent_cnt_check' ) == 'Y' ? _elm( $requests, 'i_recent_cnt' ) : 0 ;

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();
        if( empty( $aData ) === true ){
            $aStatus                               = $cartModel->insertCartSetting( $modelParam );
        }else{
            $aStatus                               = $cartModel->updateCartSetting( $modelParam );
        }

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['alert']                     = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '장바구니 설정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE )." / newData:".json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['alert']                     = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        $this->db->transCommit();

        $response['status']                        = 200;
        $response['alert']                         = '설정이 완료되었습니다.';
        $response['redirect_url']                  = _link_url( '/setting/cart' );

        return $this->respond( $response );

    }



}