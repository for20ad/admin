<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\DeliveryModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class DeliveryApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }

    public function addDeliveryCompany()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $deliveryModel                              = new DeliveryModel();

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
            'i_company_name' => [
                'label'  => '업체명',
                'rules'  => 'trim|required|is_unique[DELIVERY_COMPANY.D_COMPANY_NAME]',
                'errors' => [
                    'required'      => '업체명을 입력하세요.',
                    'is_unique'     => '업체명이 이미 존재합니다.',
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
        $max_sort                                   = $deliveryModel->getDeliveryCompanyMaxSortNum();
        $modelParam                                 = [];

        $modelParam['D_COMPANY_NAME']               = _elm( $requests, 'i_company_name' );
        $modelParam['D_TRACE_URL']                  = _elm( $requests, 'i_trace_url' );
        $modelParam['D_COMPANY_EXT']                = _elm( $requests, 'i_company_ext' );
        $modelParam['D_SORT']                       = (int) $max_sort + 1;
        $modelParam['D_FIX_YN']                     = _elm( $requests, 'i_fix_yn' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aStatus                                    = $deliveryModel->insertDelivryCompany( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '배송업체 추가 - newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
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
        $response['alert']                          = '등록이 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/deliveryComp' );

        return $this->respond( $response );

    }

    public function modifyDeliveryComp( $param = [] )
    {
        $pageDatas                                  = [];
        $requests                                   = $this->request->getPost();

        $deliveryModel                              = new DeliveryModel();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        if( count( _elm($requests, 'i_idx' ) ) < 1 ){
            $response['status']                     = 400;
            $response['alert']                      = '수정할 업체를 선택해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 등급 수정
        #------------------------------------------------------------------
        $this->db->transBegin();

        foreach( _elm( $requests, 'i_idx' )  as $vCompanyIdx ){
            $aData                                  = $deliveryModel->getDeliveryCompanyByIdx($vCompanyIdx);
            $modelParam                             = [];
            $modelParam['D_COMPANY_NAME']           = _elm( _elm( $requests, 'i_company_name' ), $vCompanyIdx, '');
            $modelParam['D_TRACE_URL']              = _elm( _elm( $requests, 'i_trace_url' ), $vCompanyIdx, '');
            $modelParam['D_COMPANY_EXT']            = _elm( _elm( $requests, 'i_company_ext' ), $vCompanyIdx, '');
            $modelParam['D_FIX_YN']                 = _elm( _elm( $requests, 'i_fix_yn' ), $vCompanyIdx, '');

            $modelParam['D_IDX']                    = $vCompanyIdx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aResult                                = $deliveryModel->updateDeliveryCompany($modelParam);

            if ( $this->db->transStatus() === false || $aResult === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response, 400 );
            }

            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '배송업체 수정 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ). ' // newData::'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);

    }

    public function updateDeliveryCompanySort()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $deliveryModel                              = new DeliveryModel();

        $this->db->transBegin();

        foreach( _elm( $requests, 'sort' ) as $sort => $gidx ){
            $modelParam                             = [];
            $modelParam['D_SORT']                   = $sort + 1;
            $modelParam['D_IDX']                    = $gidx;

            $aStatus                                = $deliveryModel->setDeliveryCompanySort( $modelParam );

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

    public function deletedeliveryComp( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $deliveryModel                              = new DeliveryModel();
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        #------------------------------------------------------------------
        # TODO: 삭제 진행
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['D_IDX']                        = _elm( $requests, 'd_idx' );
        $aData                                      = $deliveryModel->getDeliveryCompanyByIdx( _elm( $requests, 'd_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '배송업체 데이터가 없습니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $aStatus                                    = $deliveryModel->deleteDeliveryCompany( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '배송업체 삭제 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/deliveryComp' );

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);
    }
}