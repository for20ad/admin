<?php
#------------------------------------------------------------------
# PopupApi.php
# 팝업 API
# 김우진
# 2024-08-06 13:45:46
# @Desc :
#------------------------------------------------------------------
namespace Module\design\Controllers\apis;

use Module\core\Controllers\ApiController;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

use Module\design\Models\PopupModel;

#------------------------------------------------------------------
# FIXME: editor 데이터 html 변환라이브러리
#------------------------------------------------------------------
use League\CommonMark\CommonMarkConverter;

class PopupApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $popupModel;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->popupModel                           = new PopupModel();
    }

    public function getPopupLists( $param = [] )
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
            $modelParam['P_VIEW_GBN']               = _elm( $requests, 's_view_gbn' );
        }
        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['P_STATUS']                 = _elm( $requests, 's_status' );
        }
        if( empty( _elm( $requests, 's_start_date' ) ) === false && empty( _elm( $requests, 's_end_date' ) ) === false ){
            $modelParam['P_PERIOD_START_AT']        = date( 'Y-m-d', strtotime( _elm( $requests, 's_start_date' ) ) );
            $modelParam['P_PERIOD_END_AT']          = date( 'Y-m-d', strtotime( _elm( $requests, 's_end_date' ) ) );
        }

        if( empty( _elm( $requests , 's_popup_title' ) ) === false )
        {
            $modelParam['P_TITLE']                  = _elm( $requests, 's_popup_title' );
        }

        $modelParam['order']                        = 'P_CREATE_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->popupModel->getPopupLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$popup;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\design\Views\popup\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/design/popup' ), '/');
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


    public function popupRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$popup;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\design\Views\popup\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }
    public function popupRegisterProc()
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
        $validation->setRules([
            'i_title' => [
                'label'  => '팝업 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '팝업 제목을 입력하세요.',
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
            'i_content' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '팝업내용을',
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
        $modelParam['P_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['P_VIEW_GBN']                   = _elm( $requests, 'i_view_gbn');
        $modelParam['P_STATUS']                     = _elm( $requests, 'i_status');
        $modelParam['P_PERIOD_START_AT']            = _elm( $requests, 'i_period_start_date_time');
        $modelParam['P_PERIOD_END_AT']              = _elm( $requests, 'i_period_end_date_time');
        $modelParam['P_LOCATE']                     = _elm( $requests, 'i_locate');
        $modelParam['P_LINK_URL']                   = _elm( $requests, 'i_link_url');
        $modelParam['P_WIDTH']                      = _elm( $requests, 'i_width');
        $modelParam['P_HEIGHT']                     = _elm( $requests, 'i_height');
        $modelParam['P_CLOSE_YN']                   = _elm( $requests, 'i_close_yn');
        $modelParam['P_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['P_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['P_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 임시 디렉토리와 최종 저장 디렉토리 경로
        #------------------------------------------------------------------
        $tempDir                                    = WRITEPATH . 'uploads/temp/';
        $finalDir                                   = WRITEPATH . 'uploads/';

        #------------------------------------------------------------------
        # TODO: 글 내용에서 파일 경로 추출 (Markdown 형식의 이미지 URL 추출)
        #------------------------------------------------------------------
        $i_content                                  = _elm($requests, 'i_content');
        preg_match_all('/!\[.*?\]\((.*?)\)/', $i_content, $matches);
        $imageUrls                                  = $matches[1];

        foreach ($imageUrls as $imageUrl) {
            if (strpos($imageUrl, 'uploads/temp/') !== false) {
                #------------------------------------------------------------------
                # TODO: 임시 경로를 최종 경로로 변경
                #------------------------------------------------------------------
                $tempFilePath = str_replace(base_url() . 'uploads/temp/', $tempDir, $imageUrl);
                $finalFilePath = str_replace(base_url() . 'uploads/temp/', $finalDir, $imageUrl);

                #------------------------------------------------------------------
                # TODO: 최종 디렉토리가 없으면 생성
                #------------------------------------------------------------------
                if (!is_dir($finalDir)) {
                    mkdir($finalDir, 0777, true);
                }
                #------------------------------------------------------------------
                # TODO: 파일 이동
                #------------------------------------------------------------------
                if (file_exists($tempFilePath)) {
                    if (rename($tempFilePath, $finalFilePath)) {
                        #------------------------------------------------------------------
                        # TODO: 파일 이동이 완료되면 임시 파일 삭제
                        #------------------------------------------------------------------
                        if (file_exists($tempFilePath)) {
                            unlink($tempFilePath);
                        }
                    }
                }

                #------------------------------------------------------------------
                # TODO: 글 내용에서 경로 업데이트
                #------------------------------------------------------------------
                $newImageUrl                        = str_replace(base_url() . 'uploads/temp/', base_url() . 'uploads/', $imageUrl);
                $i_content                          = str_replace($imageUrl, $newImageUrl, $i_content);
            }
        }

        #------------------------------------------------------------------
        # TODO:  수정된 내용으로 저장
        #------------------------------------------------------------------
        $modelParam['P_CONTENT']                    = htmlspecialchars( $i_content );

        $this->db->transBegin();
        $aIdx                                       = $this->popupModel->insertPopup( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '팝업 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function popupDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        if( empty( _elm( $requests, 'popup_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$popup;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $this->popupModel->getPopupDataByIdx( _elm( $requests, 'popup_idx' ) );


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

        $page_datas['detail']                       = view( '\Module\design\Views\popup\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function popupDetailProc()
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
        $validation->setRules([
            'i_title' => [
                'label'  => '팝업 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '팝업 제목을 입력하세요.',
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
            'i_content' => [
                'label'  => '',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '팝업내용을 입력하세요.',
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
        $modelParam['P_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $this->popupModel->getPopupDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $modelParam['P_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['P_VIEW_GBN']                   = _elm( $requests, 'i_view_gbn');
        $modelParam['P_STATUS']                     = _elm( $requests, 'i_status');
        $modelParam['P_PERIOD_START_AT']            = _elm( $requests, 'i_period_start_date_time');
        $modelParam['P_PERIOD_END_AT']              = _elm( $requests, 'i_period_end_date_time');
        $modelParam['P_LOCATE']                     = _elm( $requests, 'i_locate');
        $modelParam['P_LINK_URL']                   = _elm( $requests, 'i_link_url');
        $modelParam['P_WIDTH']                      = _elm( $requests, 'i_width');
        $modelParam['P_HEIGHT']                     = _elm( $requests, 'i_height');
        $modelParam['P_CLOSE_YN']                   = _elm( $requests, 'i_close_yn');
        $modelParam['P_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['P_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['P_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 임시 디렉토리와 최종 저장 디렉토리 경로
        #------------------------------------------------------------------
        $tempDir                                    = WRITEPATH . 'uploads/temp/';
        $finalDir                                   = WRITEPATH . 'uploads/';

        #------------------------------------------------------------------
        # TODO: 글 내용에서 파일 경로 추출 (Markdown 형식의 이미지 URL 추출)
        #------------------------------------------------------------------
        $i_content                                  = _elm($requests, 'i_content');
        preg_match_all('/!\[.*?\]\((.*?)\)/', $i_content, $matches);
        $imageUrls                                  = $matches[1];

        foreach ($imageUrls as $imageUrl) {
            if (strpos($imageUrl, 'uploads/temp/') !== false) {
                #------------------------------------------------------------------
                # TODO: 임시 경로를 최종 경로로 변경
                #------------------------------------------------------------------
                $tempFilePath                       = str_replace(base_url() . 'uploads/temp/', $tempDir, $imageUrl);
                $finalFilePath                      = str_replace(base_url() . 'uploads/temp/', $finalDir, $imageUrl);

                #------------------------------------------------------------------
                # TODO: 최종 디렉토리가 없으면 생성
                #------------------------------------------------------------------
                if (!is_dir($finalDir)) {
                    mkdir($finalDir, 0777, true);
                }
                #------------------------------------------------------------------
                # TODO: 파일 이동
                #------------------------------------------------------------------
                if (file_exists($tempFilePath)) {
                    if (rename($tempFilePath, $finalFilePath)) {
                        #------------------------------------------------------------------
                        # TODO: 파일 이동이 완료되면 임시 파일 삭제
                        #------------------------------------------------------------------
                        if (file_exists($tempFilePath)) {
                            unlink($tempFilePath);
                        }
                    }
                }

                #------------------------------------------------------------------
                # TODO: 글 내용에서 경로 업데이트
                #------------------------------------------------------------------
                $newImageUrl                        = str_replace(base_url() . 'uploads/temp/', base_url() . 'uploads/', $imageUrl);
                $i_content                          = str_replace($imageUrl, $newImageUrl, $i_content);
            }
        }

        #------------------------------------------------------------------
        # TODO:  수정된 내용으로 저장
        #------------------------------------------------------------------
        $modelParam['P_CONTENT']                    = htmlspecialchars( $i_content );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->popupModel->updatePopup( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '팝업 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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
    public function deletePopup()
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
        $modelParam['P_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $this->popupModel->getPopupDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->popupModel->deletePopup( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------

        $finalDir                                   = WRITEPATH . 'uploads/';

        $i_content                                  = _elm($aData, 'P_CONTENT');
        preg_match_all('/!\[.*?\]\((.*?)\)/', $i_content, $matches);
        $imageUrls                                  = $matches[1];

       // 이미지 URL 처리
        foreach ($imageUrls as $imageUrl) {
            if (strpos($imageUrl, 'uploads/') !== false) {
                // 최종 경로 파일 삭제
                $finalFilePath                      = str_replace(base_url() . 'uploads/', $finalDir, $imageUrl);

                if (file_exists($finalFilePath)) {
                    @unlink($finalFilePath);
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '팝업 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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

    public function loadPopup(){
        $owensView                                  = new OwensView();
        $modelParam                                 = [];
        $modelParam['location']                     = 'ADMIN';

        $popDatas                                   = $this->popupModel->getPopupDatas( $modelParam );

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $detail                                     = [];
        if( empty( $popDatas ) === false ){
            $converter                              = new CommonMarkConverter();
            foreach( $popDatas as $key => $popup ){
                $popup['P_CONTENT']                 = nl2br( htmlspecialchars( $converter->convertToHtml( htmlspecialchars_decode( _elm( $popup, 'P_CONTENT' ) ) ) ) ) ;

                $view_datas                         = [];
                $view_datas['aData']                = $popup;

                $owensView->setViewDatas( $view_datas );
                $detail[$key]                       = view( '\Module\design\Views\popup\_makePopup' , ['owensView' => $owensView] );
            }
        }


        $response['status']                         = 200;
        $response['popups']                         = $detail;

        return $this->respond( $response );

    }



}