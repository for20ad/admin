<?php
#------------------------------------------------------------------
# BannerApi.php
# 배너관리 API
# 김우진
# 2024-08-08 09:17:45
# @Desc :
#------------------------------------------------------------------
namespace Module\design\Controllers\apis;

use Module\core\Controllers\ApiController;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

use Module\design\Models\BannerModel;

#------------------------------------------------------------------
# FIXME: editor 데이터 html 변환라이브러리
#------------------------------------------------------------------
use League\CommonMark\CommonMarkConverter;

class BannerApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $bannerModel;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->bannerModel                           = new BannerModel();
    }

    public function getBannerLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
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
        if( empty( _elm( $requests, 's_view_gbn' ) ) === false ){
            $modelParam['B_VIEW_GBN']               = _elm( $requests, 's_view_gbn' );
        }
        if( empty( _elm( $requests, 's_locate' ) ) === false ){
            $modelParam['B_VIEW_GBN']               = _elm( $requests, 's_view_gbn' );
        }
        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['B_STATUS']                 = _elm( $requests, 's_status' );
        }
        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['B_PERIOD_START_AT']        = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['B_PERIOD_END_AT']          = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        if( empty( _elm( $requests , 's_popup_title' ) ) === false )
        {
            $modelParam['B_TITLE']                  = _elm( $requests, 's_popup_title' );
        }

        $modelParam['order']                        = 'B_CREATE_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->bannerModel->getBannerLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$banner;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\design\Views\banner\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/design/banner' ), '/');
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
        $logParam['MB_HISTORY_CONTENT']             = '팝업 등록 - data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE ) ;
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
        $logParam['MB_HISTORY_CONTENT']             = '배너 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE )  ;
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
        $logParam['MB_HISTORY_CONTENT']             = '배너 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
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
        $logParam['MB_HISTORY_CONTENT']             = '배너 이미지 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
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



}