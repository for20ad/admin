<?php
#------------------------------------------------------------------
# MembershipApi.php
# 회원관련 API
# 김우진
# 2024-07-16 08:59:20
# @Desc :
#------------------------------------------------------------------

namespace Module\membership\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\membership\Models\MembershipModel;
use Module\membership\Models\MileageModel;
use Module\setting\Models\CodeModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;
use Module\membership\Config\Config as membershipConfig;
use Config\Site as SiteConfig;


class MembershipApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $membershipModel;
    protected $mileageModel;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->membershipModel                      = new MembershipModel();
        $this->mileageModel                         = new MileageModel();
    }
    public function getMileageHistoryList( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();

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
        $per_page                                   = 10;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 10;
        }



        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO:  리스트 가져오기
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['M_MB_IDX']                     = _elm( $requests, 'memIdx' );



        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' M_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' M_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' M_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->mileageModel->getUserMileageHistoryLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);
        $total_page                                 = ceil( $total_count / $per_page );
        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );


            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $aConfig                                = new membershipConfig();
            $view_datas['aConfig']                  = $aConfig->member;

            $owensView->setViewDatas( $view_datas );


            $page_datas['detail']                   = view( '\Module\membership\Views\mileage_history_lists_row' , ['owensView' => $owensView] );


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
        $response['total_page']                     = $total_page;



        return $this->respond($response);
    }

    public function initUserMileage( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm($param, 'post');
        }

        if( empty( _elm( $requests, 'S_MB_IDX' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '회원 아이디값 누락. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $modelParam                                 = [];
        $modelParam['S_MB_IDX']                     = _elm( $requests, 'S_MB_IDX' );
        #------------------------------------------------------------------
        # TODO: 중복체크
        #------------------------------------------------------------------
        $aData                                      = $this->mileageModel->getMileageSummeryDataByMbIdx( _elm( $requests, 'MB_IDX' ) );
        if( !empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '이미 존재하는 데이터입니다.';

            return $this->respond( $response );
        }
        $modelParam['ADD_MILEAGE']                  = 0;
        $modelParam['USE_MILEAGE']                  = 0;
        $modelParam['DED_MILEAGE']                  = 0;
        $modelParam['EXP_MILEAGE']                  = 0;
        $modelParam['LAST_UPDATE_AT']               = date('Y-m-d H:i:s');
        $modelParam['LAST_UPDATE_IP']               = _elm( $_SERVER, 'REMOTE_ADDR' );

        $this->db->transBegin();

        $aIdx                                       = $this->mileageModel->insertUserMileageSummaryData( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
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

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        $response                                   = $this->_unset( $response );
        $response['status']                         = 200;
        $response['alert']                          = '등록이 완료되었습니다.';
        $response['reload']                         = true;

        return $this->respond( $response );
    }


    public function mileageHistoryRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $sParam                                     = [];
        $sParam['S_MB_IDX']                         = _elm( $requests, 'i_mb_idx' );
        $aData                                      = $this->mileageModel->getMileageSummeryDataByMbIdx( $sParam );


        $modelParam                                 = [];
        $modelParam['M_MB_IDX']                     = _elm( $requests, 'i_mb_idx' );


        if( empty( $aData ) === true ){
            $postParam                              = [];
            $postParam['post']                      = [];
            $postParam['post']['S_MB_IDX']          = _elm( $modelParam, 'M_MB_IDX' );
            $postParam['post']['rawData']           = true;

            $aResult                                = $this->initUserMileage( $postParam );
            if( _elm( $aResult, 'status' ) !== 200 ){
                $response['status']                 = 400;
                $response['alert']                  = _elm( $aResult, 'alert' );

                return $this->respond( $response );
            }
        }
        #------------------------------------------------------------------
        # TODO: 데이터 없어서 추가 할수도 있으므로 다시 뽑아온다.
        #------------------------------------------------------------------
        $aData                                      = $this->mileageModel->getMileageSummeryDataByMbIdx( $sParam );
        #------------------------------------------------------------------
        # TODO: 마일리지 계산하여 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['M_ADM_IDX']                    = _elm( $this->session->get('_memberInfo') , 'member_idx' );
        $modelParam['M_TYPE']                       = 'e';
        $modelParam['M_TYPE_CD']                    = 'ETC';
        $modelParam['M_GBN']                        = _elm( $requests, 'i_regist_type' );
        $modelParam['M_BEFORE_MILEAGE']             = _elm( $aData, 'ADD_MILEAGE', 0, true ) - _elm( $aData, 'USE_MILEAGE', 0, true ) - _elm( $aData, 'DED_MILEAGE', 0, true ) - _elm( $aData, 'EXP_MILEAGE', 0, true );
        $modelParam['M_AFTER_MILEAGE']              = _elm( $requests, 'i_regist_type' ) == 'add' ? _elm( $modelParam, 'M_BEFORE_MILEAGE', 0, true ) + _elm( $requests, 'i_mileage' ) :  _elm( $modelParam, 'M_BEFORE_MILEAGE', 0, true ) - _elm( $requests, 'i_mileage' );
        $modelParam['M_MILEAGE']                    = _elm( $requests, 'i_mileage' );
        $modelParam['M_REASON_CD']                  = _elm( $requests, 'i_reason_cd' );
        $modelParam['M_REASON_MSG']                 = _elm( $requests, 'i_reason_msg' );
        $modelParam['M_EXPIRE_DATE']                = _elm( $requests, 'i_expire_date' );
        $modelParam['M_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['M_CREATE_IP']                  = _elm( $_SERVER, 'REMOTE_ADDR' );

        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $aIdx                                       = $this->mileageModel->setMileageHistory( $modelParam );
        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '포인트 입력 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: summery 업데이트
        #------------------------------------------------------------------
        $sumParam                                    = [];
        $sumParam['S_IDX']                           = _elm( $aData, 'S_IDX' );

        switch( _elm( $requests, 'i_regist_type' ) ){
            case 'add':
                $sumParam['ADD_MILEAGE']             = _elm( $aData, 'ADD_MILEAGE' ) + _elm( $requests, 'i_mileage' );
                $type_txt                            = '지급';
                break;
            case 'minus':
                $sumParam['DED_MILEAGE']             = _elm( $aData, 'DED_MILEAGE' ) + _elm( $requests, 'i_mileage' );
                $type_txt                            = '삭제';
                break;
            case 'use':
                $sumParam['USE_MILEAGE']             = _elm( $aData, 'USE_MILEAGE' ) + _elm( $requests, 'i_mileage' );
                $type_txt                            = '사용';
                break;
            case 'expire':
                $sumParam['EXP_MILEAGE']             = _elm( $aData, 'EXP_MILEAGE' ) + _elm( $requests, 'i_mileage' );
                $type_txt                            = '만료';
                break;
        }

        $sumParam['LAST_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $sumParam['LAST_UPDATE_IP']                  = _elm( $_SERVER, 'REMOTE_ADDR' );

        $aStatus                                     = $this->mileageModel->updateMileageSummery( $sumParam );
        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '포인트 합산 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '포인트 '.$type_txt.' - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ) .'newData:'.json_encode( $sumParam, JSON_UNESCAPED_UNICODE ) ;
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
        $response['alert']                          = '정상처리 되었습니다.';

        return $this->respond( $response );

    }





    public function memberDelete()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['MB_IDX']                       = _elm( $requests, 'i_mb_idx' );
        $modelParam['MB_STATUS']                    = '9';

        $aData                                      = $this->membershipModel->getMembershipDataByIdx( _elm( $modelParam, 'MB_IDX' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 500;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 상태값 업데이트 (삭제처리)
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->membershipModel->updateMembershipStatus( $modelParam );
        if ( $this->db->transStatus() === false  || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();
        unset( $response['redirect_url'] );
        unset( $response['replace_url'] );
        $response['status']                         = 200;
        $response['alert']                          = '삭제가 완료되었습니다.';

        return $this->respond( $response );

    }

    public function register()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 데티어 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = new membershipConfig;
        $aConfig                                    = $aConfig->member;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $_member_grade                              = $this->membershipModel->getMembershipGrade();
        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $view_datas['member_grades']                = $member_grade;


        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['aConfig']                      = $aConfig;

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\membership\Views\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }

    public function detail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        if( empty( _elm($requests, 'memIdx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데티어 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = new membershipConfig;
        $aConfig                                    = $aConfig->member;

        $_member_grade                              = $this->membershipModel->getMembershipGrade();
        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }
        $view_datas['member_grades']                = $member_grade;
        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];

        $aData                                      = $this->membershipModel->getMembershipDataByIdx( _elm($requests, 'memIdx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $aData['MB_MOBILE_NUM_DEC']                 = _add_dash_tel_num( $this->_aesDecrypt( _elm(  $aData, 'MB_MOBILE_NUM' ) ) );
        $aData['MB_EMAIL_DEC']                      = $this->_aesDecrypt( _elm(  $aData, 'MB_EMAIL' ) );

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $result                                     = $aData;

        $view_datas['aData']                        = $result;
        $view_datas['aConfig']                      = $aConfig;

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\membership\Views\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }


    public function mileageRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();


        #------------------------------------------------------------------
        # TODO: config 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = new membershipConfig;
        $aConfig                                    = $aConfig->member;

        $mConfig                                    = new SiteConfig();
        $mConfig                                    = $mConfig->mileage;


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $codeParam                                  = [];
        $codeParam['C_NAME']                        = '지급/삭감';
        $codeModel                                  = new CodeModel();
        $_aCodeDatas                                = $codeModel->getCodesByNameTop( $codeParam );
        $aCodeDatas                                 = [];
        if( $_aCodeDatas ){
            foreach( $_aCodeDatas as $key => $data ){
                $aCodeDatas[_elm( $data, 'C_IDX' )] = _elm( $data, 'C_NAME' );
            }
        }
        $view_datas['aCodes']                       = $aCodeDatas;


        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['aConfig']                      = $aConfig;


        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\membership\Views\_mileage_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }
    public function mileageUserRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();
        #------------------------------------------------------------------
        # TODO: config 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = new membershipConfig;
        $aConfig                                    = $aConfig->member;

        $mConfig                                    = new SiteConfig();
        $mConfig                                    = $mConfig->mileage;

        #------------------------------------------------------------------
        # TODO: 회원정보 로드
        #------------------------------------------------------------------
        $mData                                      = $this->membershipModel->getMembershipDataByIdx( _elm( $requests, 'memIdx' ) );
        if( empty( $mData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '회원정보가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }



        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $codeParam                                  = [];
        $codeParam['C_NAME']                        = '지급/삭감';
        $codeModel                                  = new CodeModel();
        $_aCodeDatas                                = $codeModel->getCodesByNameTop( $codeParam );
        $aCodeDatas                                 = [];
        if( $_aCodeDatas ){
            foreach( $_aCodeDatas as $key => $data ){
                $aCodeDatas[_elm( $data, 'C_IDX' )] = _elm( $data, 'C_NAME' );
            }
        }
        $view_datas['aCodes']                       = $aCodeDatas;


        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $view_datas['aConfig']                      = $aConfig;
        $view_datas['mData']                        = $mData;

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\membership\Views\_mileage_user_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }



    public function getSubMileageLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();

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
        $per_page                                   = 5;

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
        $modelParam['MB_STATUS']                    = _elm( $requests, 's_status' );




        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_NM']            = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests , 's_keyword' ) ) );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' MB_REG_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->membershipModel->getMemberShipMileageLists($modelParam);

        if (!empty(_elm($aLISTS_RESULT, 'lists'))) {
            foreach (_elm($aLISTS_RESULT, 'lists') as $key => $list) {
                $aLISTS_RESULT['lists'][$key]['MB_MOBILE_NUM_DEC'] = $this->_aesDecrypt(_elm($list, 'MB_MOBILE_NUM'));
            }
        }



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


            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $aConfig                                = new membershipConfig();
            $view_datas['aConfig']                  = $aConfig->member;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\membership\Views\sub_lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/membership/lists' ), '/');
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

    public function getMembershipMileageLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();

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
        $modelParam['MB_STATUS']                    = _elm( $requests, 's_status' );




        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_NM']            = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests , 's_keyword' ) ) );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' MB_REG_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->membershipModel->getMemberShipMileageLists($modelParam);

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


            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $aConfig                                = new membershipConfig();
            $view_datas['aConfig']                  = $aConfig->member;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\membership\Views\mileage_lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/membership/lists' ), '/');
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

    public function getMembershipLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();

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
        $modelParam['MB_STATUS']                    = _elm( $requests, 's_status' );
        $modelParam['MB_GRADE_IDX']                 = _elm( $requests, 's_grade' );
        $modelParam['START_DATE']                   = _elm( $requests, 's_start_date' );
        $modelParam['END_DATE']                     = _elm( $requests, 's_end_date' );




        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_NM']            = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests , 's_keyword' ) ) );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' MB_REG_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;



        $aLISTS_RESULT                              = $this->membershipModel->getMemberShipLists($modelParam);
        if( empty( _elm( $aLISTS_RESULT , 'lists' ) ) === false ){
            foreach( _elm( $aLISTS_RESULT , 'lists' ) as $key => $list ){
                $aLISTS_RESULT['lists'][$key]['MB_MOBILE_NUM_DEC'] = $this->_aesDecrypt( _elm( $list , 'MB_MOBILE_NUM' )  );
            }
        }

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


            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $aConfig                                = new membershipConfig();
            $view_datas['aConfig']                  = $aConfig->member;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\membership\Views\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/membership/lists' ), '/');
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

    public function getMileageListsExcel(){

        $requests                                   = $this->request->getPost();

        $modelParam                                 = [];

        $modelParam['MB_STATUS']                    = _elm($requests, 's_status');
        $modelParam['END_DATE']                     = _elm($requests, 's_end_date');


        if (!empty(_elm($requests, 's_keyword'))) {
            switch (_elm($requests, 's_condition')) {
                case 'mb_id':
                    $modelParam['MB_USERID']        = _elm($requests, 's_keyword');
                    break;
                case 'mb_name':
                    $modelParam['MB_NM']            = _elm($requests, 's_keyword');
                    break;
                case 'mb_mobile':
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt(preg_replace('/[^0-9]/', '', _elm($requests, 's_keyword')));
                    break;
            }
        }

        switch (_elm($requests, 'ordering')) {
            case 'regdate_desc':
                $modelParam['order']                = ' LAST_UPDATE_AT DESC';
                break;
            case 'regdate_asc':
                $modelParam['order']                = ' LAST_UPDATE_AT ASC';
                break;
            default:
                $modelParam['order']                = ' LAST_UPDATE_AT DESC';
                break;
        }

        $aLISTS_RESULT = $this->membershipModel->getMemberShipMileageLists($modelParam);

        $this->exportExcel(_elm($aLISTS_RESULT, 'lists'), _elm($requests, 's_form_idx'), date('Ymd').'_회원포인트리스트');

    }



    public function getListsExcel(){

        $requests                                   = $this->request->getPost();

        $modelParam                                 = [];
        $modelParam['MB_STATUS']                    = _elm($requests, 's_status');
        $modelParam['MB_GRADE_IDX']                 = _elm($requests, 's_grade');
        $modelParam['START_DATE']                   = _elm($requests, 's_start_date');
        $modelParam['END_DATE']                     = _elm($requests, 's_end_date');


        if (!empty(_elm($requests, 's_keyword'))) {
            switch (_elm($requests, 's_condition')) {
                case 'mb_id':
                    $modelParam['MB_USERID']        = _elm($requests, 's_keyword');
                    break;
                case 'mb_name':
                    $modelParam['MB_NM']            = _elm($requests, 's_keyword');
                    break;
                case 'mb_mobile':
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt(preg_replace('/[^0-9]/', '', _elm($requests, 's_keyword')));
                    break;
            }
        }

        switch (_elm($requests, 'ordering')) {
            case 'regdate_desc':
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
            case 'regdate_asc':
                $modelParam['order']                = ' MB_REG_AT ASC';
                break;
            case 'update_desc':
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc':
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default:
                $modelParam['order']                = ' MB_REG_AT DESC';
                break;
        }

        $aLISTS_RESULT = $this->membershipModel->getMemberShipLists($modelParam);
        if (!empty(_elm($aLISTS_RESULT, 'lists'))) {
            foreach (_elm($aLISTS_RESULT, 'lists') as $key => $list) {
                $aLISTS_RESULT['lists'][$key]['MB_MOBILE_NUM_DEC'] = $this->_aesDecrypt(_elm($list, 'MB_MOBILE_NUM'));
            }
        }

        $this->exportExcel(_elm($aLISTS_RESULT, 'lists'), _elm($requests, 's_form_idx'), date('Ymd').'_회원리스트');

    }


    public function modifyMembershipStatus( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        if( count( _elm($requests, 'i_member_idx' ) ) < 1 ){
            $response['status']                     = 400;
            $response['alert']                      = '수정할 회원을 선택해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 등급 수정
        #------------------------------------------------------------------
        $this->db->transBegin();

        foreach( _elm( $requests, 'i_member_idx' )  as $vMemberIdx ){
            $aData                                  = $this->membershipModel->getMembershipDataByIdx($vMemberIdx);
            $modelParam                             = [];
            $modelParam['MB_IDX']                   = $vMemberIdx;
            $modelParam['MB_STATUS']                = _elm( $requests, 'i_status' );

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aResult                                = $this->membershipModel->updateMembershipStatus($modelParam);

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
            $logParam['MB_HISTORY_CONTENT']         = '회원 상태 변경 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ). ' // newData::'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';


        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        unset($response["redirect_url"]);
        unset($response["replace_url"]);
        return $this->respond($response);

    }

    public function getListInButtonSet( $param = [] )
    {
        helper(['owens_form']);
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $wait_members                               = $this->membershipModel->getWaitMembershipMembers();
        $button                                     = '';

        if( $wait_members > 0  ){
            if( _elm($requests, 'xStatus' )  == 1 ){
                $button                                .= '대기회원 '.number_format($wait_members). '명';

                $button                                .= getButton([
                    'text' => '전체',
                    'class' => 'btn',
                    'style' => 'width: 60px; height:34px',
                    'extra' => [
                        'onclick' => '$("#frm_search [name=s_status]").val("");getSearchList()',
                        'name' => 'xBtn',
                    ]
                ]);
            }else{
                $button                                .= '대기회원 '.number_format($wait_members). '명';

                $button                                .= getButton([
                    'text' => '보기',
                    'class' => 'btn btn-info',
                    'style' => 'width: 60px; height:34px',
                    'extra' => [
                        'onclick' => '$("#frm_search [name=s_status]").val("1");getSearchList()',
                        'name' => 'xBtn',
                    ]
                ]);
            }


            $button                                .=' | ';
        }

        $button                                    .= getButton([
            'text' => '선택대기',
            'class' => 'btn btn-secondary',
            'style' => 'width: 60px; height:34px',
            'extra' => [
                'onclick' => 'setWaitMembersConfirm()',
            ]
        ]);
        $button                                    .= '&nbsp;';

        $button                                    .= getButton([
            'text' => '선택승인',
            'class' => 'btn btn-success',
            'style' => 'width: 60px; height:34px',
            'extra' => [
                'onclick' => 'setApprovalMembersConfirm()',
            ]
        ]);
        $response['button']                         = $button;
        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond( $response );

    }


    public function memberRegister()
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
        $setRules                                   = [
            'i_user_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required|is_unique[MEMBERSHIP.MB_USERID]',
                'errors' => [
                    'required'      => '아이디를 입력하세요.',
                    'is_unique'     => '아이디가 이미 존재합니다.',
                ],
            ],
            'i_user_name' => [
                'label'  => '이름',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '이름을 입력하세요.',
                ],
            ],

            'i_mobile_num' => [
                'label'  => '휴대폰번호',
                'rules'  => 'trim|required|regex_match[/^01[0|1|6|7|8|9]-\d{3,4}-\d{4}$/]',
                'errors' => [
                    'required'    => '휴대폰번호를 입력하세요.',
                    'regex_match' => '유효한 휴대폰번호를 입력하세요.',
                ],
            ],
            'i_password' => [
                'label'  => '비밀번호',
                'rules'  => 'trim|required|min_length[8]|max_length[20]|regex_match[/^(?=.*[!@#$%^&*(),.?":{}|<>]).+$/]',
                'errors' => [
                    'required'   => '비밀번호를 입력하세요.',
                    'min_length' => '비밀번호는 최소 8자 이상이어야 합니다.',
                    'max_length' => '비밀번호는 최대 20자 이하이어야 합니다.',
                    'regex_match' => '비밀번호는 특수문자 1개 이상 포함해야 합니다.',
                ],
            ],
            'i_password_check' => [
                'label'  => '비밀번호 확인',
                'rules'  => 'trim|required|matches[i_password]',
                'errors' => [
                    'required' => '비밀번호 확인을 입력하세요.',
                    'matches'  => '비밀번호가 일치하지 않습니다.',
                ],
            ],
        ];

        $validation->setRules( $setRules );

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
        # TODO: 회원 저장 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        #------------------------------------------------------------------
        # FIXME: 기본정보
        #------------------------------------------------------------------

        $modelParam['MB_USERID']                    = _elm( $requests, 'i_user_id' );
        $modelParam['MB_PASSWORD']                  = $this->_aesEncrypt( _elm( $requests, 'i_password' ) );
        $modelParam['MB_NM']                        = _elm( $requests, 'i_user_name' );
        $modelParam['MB_MOBILE_NUM']                = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_mobile_num' ) ) ) ;
        $modelParam['MB_GRADE_IDX']                 = _elm( $requests, 'i_grade' );
        $modelParam['MB_STATUS']                    = _elm( $requests, 'i_status' );

        #------------------------------------------------------------------
        # FIXME: 기업정보
        #------------------------------------------------------------------
        $modelParam['MB_COM_NAME']                  = _elm( $requests, 'i_mb_com_name' );
        $modelParam['MB_COM_CEO']                   =_elm( $requests, 'i_mb_com_ceo' );
        $modelParam['MB_BUSINESS_NUMBER']           = preg_replace( '/[^0-9]/', '', _elm( $requests, 'i_mb_business_number' ) );
        $modelParam['MB_COM_SEVICE']                =_elm( $requests, 'i_mb_com_sevice' );
        $modelParam['MB_COMP_ITEM']                 =_elm( $requests, 'i_mb_comp_item' );
        $modelParam['MB_COM_ZIPCD']                 =_elm( $requests, 'i_mb_com_zipcd' );
        $modelParam['MB_COM_ADDR']                  =_elm( $requests, 'i_mb_com_addr' );
        $modelParam['MB_COM_ADDR_SUB']              =_elm( $requests, 'i_mb_com_addr_sub' );

        #------------------------------------------------------------------
        # FIXME: 추가정보
        #------------------------------------------------------------------
        $modelParam['MB_BIRTH']                     = _elm( $requests, 'i_mb_birth' );
        $modelParam['MB_GENDER']                    = _elm( $requests, 'i_mb_gender' );
        $modelParam['MB_HEIGHT']                    = _elm( $requests, 'i_mb_height' );
        $modelParam['MB_WEIGHT']                    = _elm( $requests, 'i_mb_weight' );
        $modelParam['MB_FOOT_SIZE']                 = _elm( $requests, 'i_mb_foot_size' );
        $modelParam['MB_WAIST']                     = _elm( $requests, 'i_mb_waist' );
        $modelParam['MB_ADM_MEMO']                  = htmlspecialchars( _elm( $requests, 'i_mb_adm_memo' ) );
        $modelParam['MB_REG_AT']                    = date('Y-m-d H:i:s');

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $mIdx                                       = $this->membershipModel->insertMemberData( $modelParam );
        if ( $this->db->transStatus() === false || $mIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $addrParam                                  = [];
        if( empty( _elm( $requests, 'i_zip_cd' ) ) === false && empty( _elm( $requests, 'i_addr' ) ) === false ){
            #------------------------------------------------------------------
            # TODO: 기본배송지 등록
            #------------------------------------------------------------------
            $addrParam["D_MB_IDX"]                  = _elm( $modelParam, 'MB_IDX' );
            $addrParam['D_ZIP_CD']                  = _elm( $requests, 'i_zip_cd' );
            $addrParam['D_ADDR']                    = _elm( $requests, 'i_addr' );
            $addrParam['D_ADDR_SUB']                = _elm( $requests, 'i_addr_sub' );
            $addrParam['D_DEFAULT']                 = 'Y';
            $addrParam['D_MEMO']                    = '관리자 등록';
            $addrParam['D_REG_AT']                  = date( 'Y-m-d H:i:s' );


            $dStatus                                = $this->membershipModel->insertDefaultDeliveryAddr( $addrParam );
            if ( $this->db->transStatus() === false || $dStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '배송지 입력 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 등록 - Data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        if( empty( $addrParam ) === false ){
            $logParam['MB_HISTORY_CONTENT']        .= ' // 기본배송지 등록 : '.json_encode( $addrParam, JSON_UNESCAPED_UNICODE );
        }
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

        return $this->respond( $response );
    }

    public function memberModify()
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
        $setRules                                   = [
            'i_user_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required',
                'errors' => [
                    'required'      => '아이디를 입력하세요.',
                ],
            ],
            'i_user_name' => [
                'label'  => '이름',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '이름을 입력하세요.',
                ],
            ],

            'i_mobile_num' => [
                'label'  => '휴대폰번호',
                'rules'  => 'trim|required|regex_match[/^01[0|1|6|7|8|9]-\d{3,4}-\d{4}$/]',
                'errors' => [
                    'required'    => '휴대폰번호를 입력하세요.',
                    'regex_match' => '유효한 휴대폰번호를 입력하세요.',
                ],
            ],

        ];

        if( empty( _elm( $requests, 'i_password' ) ) === false && empty( _elm( $requests, 'i_password_check' ) ) === false ){

            $setRules                              += [
                'i_password' => [
                    'label'  => '비밀번호',
                    'rules'  => 'trim|required|min_length[8]|max_length[20]|regex_match[/^(?=.*[!@#$%^&*(),.?":{}|<>]).+$/]',
                    'errors' => [
                        'required'   => '비밀번호를 입력하세요.',
                        'min_length' => '비밀번호는 최소 8자 이상이어야 합니다.',
                        'max_length' => '비밀번호는 최대 20자 이하이어야 합니다.',
                        'regex_match' => '비밀번호는 특수문자 1개 이상 포함해야 합니다.',
                    ],
                ],
                'i_password_check' => [
                    'label'  => '비밀번호 확인',
                    'rules'  => 'trim|required|matches[i_password]',
                    'errors' => [
                        'required' => '비밀번호 확인을 입력하세요.',
                        'matches'  => '비밀번호가 일치하지 않습니다.',
                    ],
                ],
            ];
        }

        $validation->setRules( $setRules );

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
        # TODO: 회원 저장 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        #------------------------------------------------------------------
        # FIXME: 기본정보
        #------------------------------------------------------------------
        $modelParam['MB_IDX']                       = _elm( $requests, 'i_mb_idx' );
        $aData                                      = $this->membershipModel->getMembershipDataByIdx( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 500;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $modelParam['MB_USERID']                    = _elm( $requests, 'i_user_id' );
        if( empty( _elm( $requests, 'i_password' ) ) === false && empty( _elm( $requests, 'i_password_check' ) ) === false ){
            $modelParam['MB_PASSWORD']              = $this->_aesEncrypt( _elm( $requests, 'i_password' ) );
        }
        $modelParam['MB_NM']                        = _elm( $requests, 'i_user_name' );
        $modelParam['MB_MOBILE_NUM']                = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_mobile_num' ) ) ) ;
        $modelParam['MB_GRADE_IDX']                 = _elm( $requests, 'i_grade' );
        $modelParam['MB_STATUS']                    = _elm( $requests, 'i_status' );

        #------------------------------------------------------------------
        # FIXME: 기업정보
        #------------------------------------------------------------------
        $modelParam['MB_COM_NAME']                  = _elm( $requests, 'i_mb_com_name' );
        $modelParam['MB_COM_CEO']                   =_elm( $requests, 'i_mb_com_ceo' );
        $modelParam['MB_BUSINESS_NUMBER']           = preg_replace( '/[^0-9]/', '', _elm( $requests, 'i_mb_business_number' ) );
        $modelParam['MB_COM_SEVICE']                =_elm( $requests, 'i_mb_com_sevice' );
        $modelParam['MB_COMP_ITEM']                 =_elm( $requests, 'i_mb_comp_item' );
        $modelParam['MB_COM_ZIPCD']                 =_elm( $requests, 'i_mb_com_zipcd' );
        $modelParam['MB_COM_ADDR']                  =_elm( $requests, 'i_mb_com_addr' );
        $modelParam['MB_COM_ADDR_SUB']              =_elm( $requests, 'i_mb_com_addr_sub' );

        #------------------------------------------------------------------
        # FIXME: 추가정보
        #------------------------------------------------------------------
        $modelParam['MB_BIRTH']                     = _elm( $requests, 'i_mb_birth' );
        $modelParam['MB_GENDER']                    = _elm( $requests, 'i_mb_gender' );
        $modelParam['MB_HEIGHT']                    = _elm( $requests, 'i_mb_height' );
        $modelParam['MB_WEIGHT']                    = _elm( $requests, 'i_mb_weight' );
        $modelParam['MB_FOOT_SIZE']                 = _elm( $requests, 'i_mb_foot_size' );
        $modelParam['MB_WAIST']                     = _elm( $requests, 'i_mb_waist' );
        $modelParam['MB_ADM_MEMO']                  = htmlspecialchars( _elm( $requests, 'i_mb_adm_memo' ) );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $mIdx                                       = $this->membershipModel->updateMemberData( $modelParam );
        if ( $this->db->transStatus() === false || $mIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $addrParam                                  = [];
        if( empty( _elm( $requests, 'i_zip_cd' ) ) === false && empty( _elm( $requests, 'i_addr' ) ) === false ){
            #------------------------------------------------------------------
            # TODO: 기본배송지 등록
            #------------------------------------------------------------------
            $addrParam["D_MB_IDX"]                  = _elm( $modelParam, 'MB_IDX' );
            $addrParam['D_ZIP_CD']                  = _elm( $requests, 'i_zip_cd' );
            $addrParam['D_ADDR']                    = _elm( $requests, 'i_addr' );
            $addrParam['D_ADDR_SUB']                = _elm( $requests, 'i_addr_sub' );
            $addrParam['D_DEFAULT']                 = 'Y';

            $dData                                  = $this->membershipModel->getDefaultDeliveryAddr( $addrParam );
            #------------------------------------------------------------------
            # TODO: INSERT
            #------------------------------------------------------------------
            if( empty( $dData ) ){
                $addrParam['D_MEMO']                = '관리자 등록';
                $addrParam['D_REG_AT']              = date( 'Y-m-d H:i:s' );

                $dStatus                            = $this->membershipModel->insertDefaultDeliveryAddr( $addrParam );
                if ( $this->db->transStatus() === false || $dStatus == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '배송지 입력 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
            #------------------------------------------------------------------
            # TODO: UPDATE
            #------------------------------------------------------------------
            else{
                $addrParam['D_IDX']                 = _elm( $dData, 'D_IDX' );
                $dStatus                            = $this->membershipModel->updateDefaultDeliveryAddr( $addrParam );
                if ( $this->db->transStatus() === false || $dStatus == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '배송지 수정 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ).' / newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        if( empty( $addrParam ) === false ){
            $logParam['MB_HISTORY_CONTENT']        .= ' // 기본배송지변경 : '.json_encode( $addrParam, JSON_UNESCAPED_UNICODE );
        }
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
        $response['alert']                          = '수정이 완료되었습니다.';

        return $this->respond( $response );
    }
}

