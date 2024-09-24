<?php

namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\goods\Models\RequiredInfoModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class RequiredInfoApi extends ApiController
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

    public function getRequiredInfoLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $infoModel                                  = new RequiredInfoModel();
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
        $modelParam['R_TITLE']                      = _elm( $requests, 's_title' );

        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_USERNAME']      = _elm( $requests , 's_keyword' );
                    break;
                case 'title' :
                    $modelParam['F_TITLE']          = _elm( $requests , 's_keyword' );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' R_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' R_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' R_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' R_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' R_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $infoModel->getRequiredInfoLists($modelParam);

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


            $page_datas['lists_row']                = view( '\Module\goods\Views\requiredInfo\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/goodsRequiredInfo' ), '/');
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

    public function requiredInfoDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'info_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $infoModel                                  = new RequiredInfoModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $infoModel->getRequiredInfoDataByIdx( _elm( $requests, 'info_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $subData                                    = $infoModel->getRequiredInfoDataDetail( _elm( $requests, 'info_idx' ) );
        $aData['detail']                            = $subData;
        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\requiredInfo\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function requiredInfoRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\requiredInfo\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }


    public function requiredInfoRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $infoModel                                  = new RequiredInfoModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_name' => [
                'label'  => '필수정보명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '필수정보명을 입력하세요.',
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
        $modelParam['R_TITLE']                      = _elm( $requests, 'i_name' );
        $modelParam['R_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['R_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['R_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );


        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 상위데이터 인서트
        #------------------------------------------------------------------
        $aIdx                                       = $infoModel->insertInfo( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 하위데이터 입력
        #------------------------------------------------------------------


        if( empty( _elm( $requests, 'keys' ) ) === false ){
            foreach( _elm( $requests, 'keys' ) as $index => $key ){
                if( !empty( $key ) ){
                    $subModelParam                  = [];
                    $subModelParam['D_PARENT_IDX']  = $aIdx;
                    $subModelParam['D_KEY']         = $key;
                    $subModelParam['D_VALUE']       = _elm( _elm( $requests, 'values' ), $index);
                    $subModelParam['D_CREATE_AT']   = date( 'Y-m-d H:i:s' );
                    $subModelParam['D_CREATE_IP']   = $this->request->getIPAddress();
                    $subModelParam['D_CREATE_MB_IDX']= _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $bStatus                        = $infoModel->insertSubInfos( $subModelParam );

                    if ( $this->db->transStatus() === false || $bStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '항목 등록 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 필수정보 등록 - data:'.json_encode( $requests, JSON_UNESCAPED_UNICODE );
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

    public function requiredInfoDetailProc()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $infoModel                                  = new RequiredInfoModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_name' => [
                'label'  => '필수정보명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '필수정보명을 입력하세요.',
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
        $modelParam['R_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $infoModel->getRequiredInfoDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam['R_TITLE']                      = _elm( $requests, 'i_name' );
        $modelParam['R_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['R_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['R_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 상위데이터 업데이트
        #------------------------------------------------------------------
        $aStatus                                    = $infoModel->updateInfo( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 하위데이터 모두 삭제 후 재입력
        #------------------------------------------------------------------
        $bStatus                                    = $infoModel->deleteSubInfos( _elm( $requests, 'i_idx' ) );

        if ( $this->db->transStatus() === false || $bStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '하위데이터 삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        if( empty( _elm( $requests, 'keys' ) ) === false ){
            foreach( _elm( $requests, 'keys' ) as $index => $key ){
                if( !empty( $key ) ){
                    $subModelParam                  = [];
                    $subModelParam['D_PARENT_IDX']  = _elm( $requests, 'i_idx' ) ;
                    $subModelParam['D_KEY']         = $key;
                    $subModelParam['D_VALUE']       = _elm( _elm( $requests, 'values' ), $index);
                    $subModelParam['D_CREATE_AT']   = date( 'Y-m-d H:i:s' );
                    $subModelParam['D_CREATE_IP']   = $this->request->getIPAddress();
                    $subModelParam['D_CREATE_MB_IDX']= _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $bStatus                        = $infoModel->insertSubInfos( $subModelParam );

                    if ( $this->db->transStatus() === false || $bStatus === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '항목 등록 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 필수정보 수정 - data:'.json_encode( $requests, JSON_UNESCAPED_UNICODE );
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
        $response['alert']                          = '수정되었습니다';

        return $this->respond( $response );
    }



    public function deleteInfo()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $iconsModel                                 = new IconsModel();
        if( empty( _elm($requests, 'i_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['R_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $iconsModel->getRequiredInfoDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: 상위 데이터 삭제
        #------------------------------------------------------------------
        $aStatus                                    = $iconsModel->deleteInfo( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상위데이터 삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 하위 데이터 삭제
        #------------------------------------------------------------------
        $bStatus                                    = $iconsModel->deleteInfoDetail( $modelParam );
        if ( $this->db->transStatus() === false || $bStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '하위데이터 삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 필수정보 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
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

}