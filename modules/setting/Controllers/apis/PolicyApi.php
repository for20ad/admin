<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\PolicyModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class PolicyApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }

    public function policyMemberSet()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $policyModel                                = new PolicyModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_accept' => [
                'label'  => '회원가입설정',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '회원가입설정 구분을 선택하세요.',
                ],
            ],
            'i_grade' => [
                'label'  => '회원등급 사용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '회원등급 사용여부를 선택하세요.',
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

        $aData                                      = $policyModel->getPolicy();

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['P_ACCEPT']                     = _elm( $requests, 'i_accept' );
        $modelParam['P_GRADE']                      = _elm( $requests, 'i_grade' );
        $modelParam['P_BANNED_IDS']                 = _elm( $requests, 'i_banned_ids' );
        $modelParam['P_PASSWORD_CHANGE_PERIOD']     = _elm( $requests, 'i_password_change_period' );

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
        $response['redirect_url']                   = _link_url( '/setting/policyMember' );

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