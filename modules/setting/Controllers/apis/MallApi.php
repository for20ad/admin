<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\PolicyModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class MallApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }

    public function policyTermsSet()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $policyModel                                = new PolicyModel();

        $aData                                      = $policyModel->getMallPolicyDatas();

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['T_AGREEMENT_TERMS']            = _elm( $requests, 'i_agreement_terms' );
        $modelParam['T_AGREEMENT_PRIVACY']          = _elm( $requests, 'i_agreement_privacy' );
        $modelParam['T_AGREEMENT_MARKETING']        = _elm( $requests, 'i_agreement_marketing' );
        $modelParam['T_AGREEMENT_THIRD_PARTY']      = _elm( $requests, 'i_agreement_third_party' );
        $modelParam['T_AGREEMENT_RECURRING']        = _elm( $requests, 'i_agreement_recurring' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();
        if( empty( $aData ) === true ){
            $aStatus                                = $policyModel->insertTerms( $modelParam );
        }else{
            $aStatus                                = $policyModel->insertTerms( $modelParam );
        }

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
        $logParam['MB_HISTORY_CONTENT']             = '약관 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE )." / newData:".json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '설정이 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/mallPolicy' );

        return $this->respond( $response );

    }

    public function policyPuchaseSet()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $policyModel                                = new PolicyModel();

        $validation                                 = \Config\Services::validation();

        $aData                                      = $policyModel->getPolicy();

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------


        $modelParam                                 = [];
        $modelParam['P_PAY_DEFAULT']                = join( '|', $requests['i_pay_default'] );
        $modelParam['P_PAY_SIMPLE']                 = join( '|', $requests['i_pay_simple'] );
        $modelParam['P_DELIVERY_END_DAYS']          = _elm( $requests, 'i_delivery_end_days' );
        $modelParam['P_PURCHASE_CONF_DAYS']         = _elm( $requests, 'i_purchase_conf_days' );
        $modelParam['P_PURCHASE_CANCLE']            = _elm( $requests, 'i_purchase_cancle' );
        $modelParam['P_PURCHASE_RETURN']            = _elm( $requests, 'i_purchase_return' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();
        if( empty( $aData ) === true ){
            $aStatus                                = $policyModel->insertPolicy( $modelParam );
        }else{
            $aStatus                                = $policyModel->updatePolicy( $modelParam );
        }

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
        $logParam['MB_HISTORY_CONTENT']             = '정책 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE )." / newData:".json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '설정이 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/policyPurchase' );

        return $this->respond( $response );

    }


}