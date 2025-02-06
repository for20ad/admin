<?php
namespace Module\board\Controllers\apis;
use Module\board\Controllers\BoardApis;

class BoardApi extends BoardApis
{

    public function getBoardLists( $param = [] )
    {
        $response                                   = $this->_initResponse();

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

        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['B_STATUS']                 = _elm( $requests, 's_status' );
        }

        if( empty( _elm( $requests , 's_title' ) ) === false )
        {
            $modelParam['B_TITLE']                  = _elm( $requests, 's_title' );
        }

        $modelParam['order']                        = 'B_CREATE_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->boardModel->getboardLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$board;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;


            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\board\Views\board\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/board' ), '/');
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

    public function boardRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();



        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;
        $boardGroup                                 = $this->boardModel->getBoardGroup();

        $_boGrp                                     = [];
        if( empty( $boardGroup ) === false ){
            foreach( $boardGroup as $gKey => $group ){
                $_boGrp[ _elm( $group, 'G_IDX' ) ]  = _elm( $group, 'G_NAME' );
            }

        }
        $aConfig['brdGrp']                          = $_boGrp;
        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\board\_register' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function boardRegisterProc()
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
                'label'  => '게시판 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 제목을 입력하세요.',
                ],
            ],
            'i_id' => [
                'label'  => '게시판 ID',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 ID를 입력하세요.',
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
        $modelParam['B_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['B_ID']                         = _elm( $requests, 'i_id' );
        $modelParam['B_GROUP']                      = _elm( $requests, 'i_group' );
        $modelParam['B_STATUS']                     = _elm( $requests, 'i_status');

        $maxSort                                    = $this->boardModel->getLastBoardSortNum();
        $modelParam['B_SORT']                       = $maxSort+1;

        $modelParam['B_IS_FREE']                    = _elm( $requests, 'i_is_free' )?? 'Y';
        $modelParam['B_HITS']                       = _elm( $requests, 'i_hit_use' )?? 'N' ;
        $modelParam['B_SECRET']                     = _elm( $requests, 'i_secret_use' )?? 'N';
        $modelParam['B_COMMENT']                    = _elm( $requests, 'i_comment_use' ) ?? 'N';
        $modelParam['B_ICON_PATH']                  = '';
        $modelParam['B_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['B_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['B_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO:동일한 게시판 id가 존재하는지 검수
        #------------------------------------------------------------------
        $sameChk                                    = $this->boardModel->sameChecked( $modelParam );
        if( empty( $sameChk ) === false ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '동일한 게시판 명이 존재합니다.';

            return $this->respond( $response );
        }


        if( empty( $files ) === false && _elm( $files, 'i_img' )->getSize() > 0 ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'path' => 'board/icon',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( $files as $file ){
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

                $modelParam['B_ICON_PATH']          = _elm( $file_return, 'uploaded_path');

            }
        }


        $this->db->transBegin();
        $aIdx                                       = $this->boardModel->insertBoard( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '게시판 생성 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function boardDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'board_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;
        $boardGroup                                 = $this->boardModel->getBoardGroup();

        $_boGrp                                     = [];
        if( empty( $boardGroup ) === false ){
            foreach( $boardGroup as $gKey => $group ){
                $_boGrp[ _elm( $group, 'G_IDX' ) ]  = _elm( $group, 'G_NAME' );
            }

        }
        $aConfig['brdGrp']                          = $_boGrp;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getboardDataByIdx( _elm( $requests, 'board_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\board\_detail' , ['owensView' => $this->owensView] );

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function boardDetailProc()
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
            'i_idx' => [
                'label'  => '게시판 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 IDX누락',
                ],
            ],
            'i_title' => [
                'label'  => '게시판 제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 제목을 입력하세요.',
                ],
            ],
            'i_id' => [
                'label'  => '게시판 ID',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 ID를 입력하세요.',
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
        $aData                                      = $this->boardModel->getBoardDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['B_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['B_ID']                         = _elm( $requests, 'i_id' );
        $modelParam['B_GROUP']                      = _elm( $requests, 'i_group' );
        $modelParam['B_STATUS']                     = _elm( $requests, 'i_status');

        $modelParam['B_IS_FREE']                    = _elm( $requests, 'i_is_free' )?? 'Y';
        $modelParam['B_HITS']                       = _elm( $requests, 'i_hit_use' )?? 'N' ;
        $modelParam['B_SECRET']                     = _elm( $requests, 'i_secret_use' )?? 'N';
        $modelParam['B_COMMENT']                    = _elm( $requests, 'i_comment_use' ) ?? 'N';
        $modelParam['B_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['B_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['B_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO:동일한 게시판 id가 존재하는지 검수
        #------------------------------------------------------------------
        if( _elm( $aData, 'B_TITLE' ) !== _elm( $requests, 'i_title' ) || _elm( $aData, 'B_ID' ) !==  _elm( $requests, 'i_id' ) ) {
            $sameChk                                    = $this->boardModel->sameChecked( $modelParam );
            if( empty( $sameChk ) === false ){
                $response['status']                     = 400;
                $response['error']                      = 400;
                $response['messages']                   = '동일한 게시판 명이 존재합니다.';

                return $this->respond( $response );
            }
        }



        if( empty( $files ) === false && _elm( $files, 'i_img' )->getSize() > 0 ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'path' => 'board/icon',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( $files as $file ){
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

                $modelParam['B_ICON_PATH']          = _elm( $file_return, 'uploaded_path');
                if( empty( _elm( $aData, 'B_ICON_PATH' ) ) === false ){
                    $finalFilePath                  = WRITEPATH . _elm( $aData, 'B_ICON_PATH' );
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

        $aStatus                                    = $this->boardModel->updateBoard( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '게시판 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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

    public function deleteBoard()
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
        $aData                                      = $this->boardModel->getBoardDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->boardModel->deleteBoard( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        // #------------------------------------------------------------------
        // # TODO: 파일 삭제
        // #------------------------------------------------------------------
        // $finalFilePath                              = WRITEPATH . _elm( $aData, 'B_IMG_PATH' );
        // if (file_exists($finalFilePath)) {
        //     @unlink($finalFilePath);
        // }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '게시판 삭제 처리 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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


    public function deleteboardIcon(){
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

        $aData                                      = $this->boardModel->getboardDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        $this->db->transBegin();
        $modelParam                                 = [];
        $modelParam['B_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['B_ICON_PATH']                  = '';

        $aStatus                                    = $this->boardModel->deleteBoardIcon( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        $finalFilePath                              = WRITEPATH . _elm( $aData, 'B_ICON_PATH' );
        if (file_exists($finalFilePath)) {
            @unlink($finalFilePath);
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '게시판 아이콘 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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

    public function getNoticeLists( $param = [] )
    {
        $response                                   = $this->_initResponse();

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
        if( empty( _elm( $requests, 's_condition' ) ) === false && empty( _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'title' :
                    $modelParam['N_TITLE']          =  _elm( $requests, 's_keyword' );
                    break;
                case 'content' :
                    $modelParam['N_CONTENT']        =  _elm( $requests, 's_keyword' );
                    break;
            }
        }
        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['N_STATUS']                 = _elm( $requests, 's_status' );
        }

        if( empty( _elm( $requests , 's_start_date' ) ) === false && empty( _elm( $requests , 's_end_date' ) ) === false)
        {
            $modelParam['N_START']                  = _elm( $requests, 's_start_date' );
            $modelParam['N_END']                    = _elm( $requests, 's_end_date' );
        }


        if( empty( _elm( $requests, 's_is_notice' ) ) === false ){
            $modelParam['N_IS_NOTICE']              = _elm( $requests, 's_is_notice' );
        }
        if( empty( _elm( $requests, 's_is_stay' ) ) === false ){
            $modelParam['N_IS_STAY']                = _elm( $requests, 's_is_stay' );
        }


        if( empty( _elm( $requests, 's_visible' ) ) === false ){
            $modelParam['N_VISIBLES']               = _elm( $requests, 's_visible' );
        }



        $modelParam['order']                        = 'N_IS_STAY DESC, N_CREATE_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->boardModel->getNoticeLists($modelParam);


        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$board;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\board\Views\notice\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/board' ), '/');
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

    public function noticeRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();



        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;
        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\notice\_register' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function noticeRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        // print_R( $requests );
        // print_r( $files );
        // die;
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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
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
        $modelParam['N_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['N_IS_VISIBLE']                 = _elm( $requests, 'i_visible' );
        $modelParam['N_CONTENT']                    = htmlspecialchars( _elm( $requests, 'i_content' ) );
        $modelParam['N_IS_STAY']                    = _elm( $requests, 'i_is_stay') ?? 'N';
        $modelParam['N_IS_NOTICE']                  = _elm( $requests, 'i_is_notice') ?? 'N';
        $modelParam['N_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['N_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['N_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['N_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if( empty( $files ) === false ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'path' => 'board/notice',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $fileFields = [
                'i_file_1' => [ 'name' =>'N_FILES_1_NAME', 'path' => 'N_FILES_1_PATH' ],
                'i_file_2' => [ 'name' =>'N_FILES_2_NAME', 'path' => 'N_FILES_2_PATH' ],
                'i_file_3' => [ 'name' =>'N_FILES_3_NAME', 'path' => 'N_FILES_3_PATH' ],
                'i_file_4' => [ 'name' =>'N_FILES_4_NAME', 'path' => 'N_FILES_4_PATH' ],
                'i_file_5' => [ 'name' =>'N_FILES_5_NAME', 'path' => 'N_FILES_5_PATH' ],
            ];


            foreach( $files as $key => $file){
                if (isset($fileFields[$key]) && $file->getSize() > 0) {
                    // 해당 파일이 있을 경우, DB 필드명에 파일 이름을 매칭

                    $file_return                    = $this->_upload( $file, $config );

                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------

                    $modelParam[ _elm( _elm( $fileFields, $key ), 'path') ] = _elm( $file_return, 'uploaded_path');
                    $modelParam[ _elm( _elm( $fileFields, $key ), 'name') ] = _elm( $file_return, 'org_name');
                }
            }
        }

        $this->db->transBegin();
        $aIdx                                       = $this->boardModel->insertNotice( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '공지사항 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function noticeDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'n_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getNoticeDataByIdx( _elm( $requests, 'n_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\notice\_detail' , ['owensView' => $this->owensView] );

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function noticeDetailProc()
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
            'i_idx' => [
                'label'  => '게시판 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 IDX누락',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
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
        $modelParam['N_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getNoticeDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['N_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['N_IS_VISIBLE']                 = _elm( $requests, 'i_visible' );
        $modelParam['N_CONTENT']                    = htmlspecialchars( _elm( $requests, 'i_content' ) );
        $modelParam['N_IS_STAY']                    = _elm( $requests, 'i_is_stay') ?? 'N';
        $modelParam['N_IS_NOTICE']                  = _elm( $requests, 'i_is_notice') ?? 'N';
        $modelParam['N_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['N_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['N_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['N_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if (!empty($files)) {
            #------------------------------------------------------------------
            # TODO: 파일 처리 설정
            #------------------------------------------------------------------
            $config = [
                'path' => 'board/notice',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $fileFields = [
                'i_file_1' => ['name' => 'N_FILES_1_NAME', 'path' => 'N_FILES_1_PATH'],
                'i_file_2' => ['name' => 'N_FILES_2_NAME', 'path' => 'N_FILES_2_PATH'],
                'i_file_3' => ['name' => 'N_FILES_3_NAME', 'path' => 'N_FILES_3_PATH'],
                'i_file_4' => ['name' => 'N_FILES_4_NAME', 'path' => 'N_FILES_4_PATH'],
                'i_file_5' => ['name' => 'N_FILES_5_NAME', 'path' => 'N_FILES_5_PATH'],
            ];

            $i=1;
            foreach ($fileFields as $key => $fieldInfo) {
                $uploadedFile = $files[$key] ?? null;
                $deleteRequested = isset($requests['delete_file_' . $i]) && $requests['delete_file_' . $i] == "1";

                $currentFilePath = _elm($aData, $fieldInfo['path']);

                // 파일 삭제 요청이 있는 경우 처리
                if ($deleteRequested && $currentFilePath) {
                    $filePath = WRITEPATH . $currentFilePath;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    // DB 필드를 비워줍니다.
                    $modelParam[$fieldInfo['path']] = '';
                    $modelParam[$fieldInfo['name']] = '';
                }


                // 새 파일이 업로드되었을 때
                if ($uploadedFile && $uploadedFile->isValid() && $uploadedFile->getSize() > 0) {
                    // 기존 파일 삭제
                    if ($currentFilePath) {
                        $filePath = WRITEPATH . $currentFilePath;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }

                    // 파일 업로드
                    $fileReturn = $this->_upload($uploadedFile, $config);

                    if (_elm($fileReturn, 'status') === false) {
                        $this->db->transRollback();
                        $response['status'] = 400;
                        $response['alert'] = _elm($fileReturn, 'error');
                        return $this->respond($response, 400);
                    }

                    // DB에 새로운 파일 데이터 설정
                    $modelParam[$fieldInfo['path']] = _elm($fileReturn, 'uploaded_path');
                    $modelParam[$fieldInfo['name']] = _elm($fileReturn, 'org_name');
                }
                $i++;
            }
        }
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aStatus                                    = $this->boardModel->updateNotice( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '공지사항 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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

    public function deleteNotice()
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
        $modelParam['N_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $this->boardModel->getNoticeDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->boardModel->deleteNotice( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        for( $i=1; $i<=5; $i++ ){
            if( empty( _elm( $aData, 'N_FILES_'.$i.'_PATH' ) ) === false ){
                $finalFilePath                              = WRITEPATH ._elm( $aData, 'N_FILES_'.$i.'_PATH' );
                if (file_exists($finalFilePath)) {
                    @unlink($finalFilePath);
                }
            }
        }



        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '공지사항 삭제 처리 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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

    public function getFaqLists( $param = [] )
    {
        $response                                   = $this->_initResponse();

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
        if( empty( _elm( $requests, 's_condition' ) ) === false && empty( _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'title' :
                    $modelParam['F_TITLE']          =  _elm( $requests, 's_keyword' );
                    break;
                case 'content' :
                    $modelParam['F_CONTENT']        =  _elm( $requests, 's_keyword' );
                    break;
                case 'answer' :
                    $modelParam['F_ANSWER']         =  _elm( $requests, 's_keyword' );
                    break;
            }
        }
        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['F_STATUS']                 = _elm( $requests, 's_status' );
        }

        if( empty( _elm( $requests, 's_is_best' ) ) === false ){
            $modelParam['F_IS_BEST']                = _elm( $requests, 's_is_best' );
        }

        if( empty( _elm( $requests, 's_cate' ) ) === false ){
            $modelParam['F_CATE']                   = _elm( $requests, 's_cate' );
        }

        $modelParam['order']                        = 'F_SORT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->boardModel->getFaqLists($modelParam);

        $cateParam                                  = [];
        $cateParam['C_PARENT_IDX']                  = '129';
        $_cateLists                                 = $this->boardModel->getFaqCode( $cateParam );
        $cateLists                                  = [];
        if( empty( $_cateLists ) === false ){
            foreach( $_cateLists as $cKey => $cate ){
                $cateLists[_elm( $cate, 'C_CODE' )] = _elm( $cate, 'C_NAME' );
            }
        }

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$board;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $view_datas['aCate']                    = $cateLists;

            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\board\Views\faq\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/board' ), '/');
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

    public function faqRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();



        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;
        $view_datas['aConfig']                      = $aConfig;

        $cateParam                                  = [];
        $cateParam['C_PARENT_IDX']                  = '129';
        $_cateLists                                 = $this->boardModel->getFaqCode( $cateParam );
        $cateLists                                  = [];
        if( empty( $_cateLists ) === false ){
            foreach( $_cateLists as $cKey => $cate ){
                $cateLists[_elm( $cate, 'C_CODE' )] = _elm( $cate, 'C_NAME' );
            }
        }

        $view_datas['aCate']                        = $cateLists;
        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );


        $page_datas['detail']                       = view( '\Module\board\Views\faq\_register' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function faqRegisterProc()
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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
                ],
            ],
            'i_answer' => [
                'label'  => '답변',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '답변을 입력하세요.',
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
        $modelParam['F_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['F_CONTENT']                    = _elm( $requests, 'i_content' );
        $modelParam['F_CATE']                       = _elm( $requests, 'i_cate' );
        $modelParam['F_ANSWER']                     = _elm( $requests, 'i_answer' );
        $modelParam['F_IS_BEST']                    = _elm( $requests, 'i_is_best') ?? 'N';
        $modelParam['F_STATUS']                     = _elm( $requests, 'i_status' );
        $maxSort                                    = $this->boardModel->getFaqMaxSort();
        $modelParam['F_SORT']                       = $maxSort;
        $modelParam['F_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['F_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['F_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if( empty( $files ) === false ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'path' => 'board/faq',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $fileFields = [
                'i_file_1' => [ 'name' =>'F_FILES_1_NAME', 'path' => 'F_FILES_1_PATH' ],
                'i_file_2' => [ 'name' =>'F_FILES_2_NAME', 'path' => 'F_FILES_2_PATH' ],
            ];


            foreach( $files as $key => $file){
                if (isset($fileFields[$key]) && $file->getSize() > 0) {
                    // 해당 파일이 있을 경우, DB 필드명에 파일 이름을 매칭

                    $file_return                    = $this->_upload( $file, $config );

                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------

                    $modelParam[ _elm( _elm( $fileFields, $key ), 'path') ] = _elm( $file_return, 'uploaded_path');
                    $modelParam[ _elm( _elm( $fileFields, $key ), 'name') ] = _elm( $file_return, 'org_name');
                }
            }
        }

        $this->db->transBegin();
        $aIdx                                       = $this->boardModel->insertFaq( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '등록 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = 'FAQ 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function faqDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'i_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getFaqDataByIdx( _elm( $requests, 'i_idx' ) );



        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        $cateParam                                  = [];
        $cateParam['C_PARENT_IDX']                  = '129';
        $_cateLists                                 = $this->boardModel->getFaqCode( $cateParam );
        $cateLists                                  = [];
        if( empty( $_cateLists ) === false ){
            foreach( $_cateLists as $cKey => $cate ){
                $cateLists[_elm( $cate, 'C_CODE' )] = _elm( $cate, 'C_NAME' );
            }
        }

        $view_datas['aCate']                        = $cateLists;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\faq\_detail' , ['owensView' => $this->owensView] );

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function faqDetailProc()
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
            'i_idx' => [
                'label'  => '게시판 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 IDX누락',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
                ],
            ],
            'i_answer' => [
                'label'  => '딥뱐',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '딥뱐을 입력하세요.',
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
        $modelParam['F_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getFaqDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['F_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['F_CONTENT']                    = _elm( $requests, 'i_content' );
        $modelParam['F_CATE']                       = _elm( $requests, 'i_cate' );
        $modelParam['F_ANSWER']                     = _elm( $requests, 'i_answer' );
        $modelParam['F_IS_BEST']                    = _elm( $requests, 'i_is_best') ?? 'N';
        $modelParam['F_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['F_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['F_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['F_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if (!empty($files)) {
            #------------------------------------------------------------------
            # TODO: 파일 처리 설정
            #------------------------------------------------------------------
            $config = [
                'path' => 'board/notice',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $fileFields = [
                'i_file_1' => ['name' => 'F_FILES_1_NAME', 'path' => 'F_FILES_1_PATH'],
                'i_file_2' => ['name' => 'F_FILES_2_NAME', 'path' => 'F_FILES_2_PATH'],
            ];

            $i=1;
            foreach ($fileFields as $key => $fieldInfo) {
                $uploadedFile = $files[$key] ?? null;
                $deleteRequested = isset($requests['delete_file_' . $i]) && $requests['delete_file_' . $i] == "1";

                $currentFilePath = _elm($aData, $fieldInfo['path']);

                // 파일 삭제 요청이 있는 경우 처리
                if ($deleteRequested && $currentFilePath) {
                    $filePath = WRITEPATH . $currentFilePath;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    // DB 필드를 비워줍니다.
                    $modelParam[$fieldInfo['path']] = '';
                    $modelParam[$fieldInfo['name']] = '';
                }


                // 새 파일이 업로드되었을 때
                if ($uploadedFile && $uploadedFile->isValid() && $uploadedFile->getSize() > 0) {
                    // 기존 파일 삭제
                    if ($currentFilePath) {
                        $filePath = WRITEPATH . $currentFilePath;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }

                    // 파일 업로드
                    $fileReturn = $this->_upload($uploadedFile, $config);

                    if (_elm($fileReturn, 'status') === false) {
                        $this->db->transRollback();
                        $response['status'] = 400;
                        $response['alert'] = _elm($fileReturn, 'error');
                        return $this->respond($response, 400);
                    }

                    // DB에 새로운 파일 데이터 설정
                    $modelParam[$fieldInfo['path']] = _elm($fileReturn, 'uploaded_path');
                    $modelParam[$fieldInfo['name']] = _elm($fileReturn, 'org_name');
                }
                $i++;
            }
        }
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aStatus                                    = $this->boardModel->updateFaq( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = 'FAQ 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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

    public function deleteFaq()
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
        $modelParam['F_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $this->boardModel->getFaqDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->boardModel->deleteFaq( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        for( $i=1; $i<=2; $i++ ){
            if( empty( _elm( $aData, 'N_FILES_'.$i.'_PATH' ) ) === false ){
                $finalFilePath                              = WRITEPATH ._elm( $aData, 'N_FILES_'.$i.'_PATH' );
                if (file_exists($finalFilePath)) {
                    @unlink($finalFilePath);
                }
            }
        }



        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = 'FAQ 삭제 처리 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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




    public function changePostsStatus(){
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $modelParam                                 = [];
        $modelParam['C_IDX']                        = _elm( $requests, 'c_idx' );
        $modelParam['C_STATUS']                     = _elm( $requests, 'c_status' );

        $this->db->transBegin();

        $aIdx                                       = $this->boardModel->updatePostsComment( $modelParam );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '댓글 저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = ' 게시물 ('. _elm( $requests, 'c_idx' ).') 댓글 '._elm( $requests, 'c_status' ) == 3 ? '삭제' : (_elm( $requests, 'c_status' ) == 1 ? '노출' : '블라인드' )  .' - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['alert']                          = '처리 완료 되었습니다.';

        return $this->respond( $response );
    }



    public function addPostsReply(){
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $requests, 'board_id' ) ) === false ){
            $bData                                  = $this->boardModel->getboardDataById( _elm( $requests, 'board_id' ) );

            if( empty( $bData ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '게시판이 존재하지 않습니다.';

                return $this->respond( $response );
            }
            $pData['C_B_IDX']                       = _elm( $requests, 'posts_idx' );
        }
        if( empty( _elm( $requests, 'c_idx' ) ) === false ){
            $pData                                  = $this->boardModel->getParentComment( _elm( $requests, 'c_idx' ) );
            if( empty( $pData ) == true ){
                $response['status']                 = 400;
                $response['alert']                  = '잘못된 댓글 IDX 입니다.';

                return $this->respond( $response );
            }

        }

        $modelParam                                 = [];

        $modelParam['C_B_IDX']                      =  _elm( $pData, 'C_B_IDX' );
        $modelParam['C_WRITER_IDX']                 = _elm( $this->session->get('_memberInfo'), 'member_idx' );
        $modelParam['C_WRITER_NAME']                = '관리자('._elm( $this->session->get('_memberInfo'), 'member_name' ).')';
        $modelParam['C_PARENT_IDX']                 = _elm( $requests, 'c_idx' );
        $modelParam['C_DEPTH']                      = _elm( $pData, 'C_DEPTH' ) + 1;
        $modelParam['C_COMMENT']                    = htmlspecialchars( nl2br( _elm( $requests, 'comment' ) ) );
        $modelParam['C_STATUS']                     = 1;
        $modelParam['C_REG_AT']                     = date( 'Y-m-d H:i:s' );
        $modelParam['C_REG_IP']                     = $this->request->getIPAddress();

        $this->db->transBegin();

        $aIdx                                       = $this->boardModel->insertPostsComment( $modelParam );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '댓글 저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = ' 게시물 ('. _elm( $requests, 'c_idx' ).') 댓글등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function getPostsComments( $param = [] )
    {
        $response                                   = $this->_initResponse();

        $requests                                   = _trim($this->request->getPost());



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
        # TODO: search Param setting
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];
        $modelParam['C_B_IDX']                      = _elm( $requests, 's_idx' );



        $modelParam['order']                        = 'C_REG_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->boardModel->getPostsCommentLists($modelParam);



        $total_count                                = _elm($aLISTS_RESULT, 'parent_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$board;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;


            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\board\Views\posts\comments_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/board/boardLists/posts' ), '/');
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

    public function getPostsLists( $param = [] )
    {
        $response                                   = $this->_initResponse();

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
        $modelParam['P_B_ID']                       =  _elm( $requests, 'bo_id' );

        if( empty( _elm( $requests, 's_condition' ) ) === false && empty( _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) ){
                case 'title' :
                    $modelParam['P_TITLE']          =  _elm( $requests, 's_keyword' );
                    break;
                case 'content' :
                    $modelParam['P_CONTENT']        =  _elm( $requests, 's_keyword' );
                    break;
                case 'writer_name' :
                    $modelParam['P_WRITER_NAME']    =  _elm( $requests, 's_keyword' );
                    break;
                case 'writer_idx' :
                    $modelParam['P_WRITER_IDX']     =  _elm( $requests, 's_keyword' );
                    break;

            }
        }
        if( empty( _elm( $requests, 's_status' ) ) === false ){
            $modelParam['P_STATUS']                 = _elm( $requests, 's_status' );
        }

        if( empty( _elm( $requests, 's_answer_status' ) ) === false ){
            $modelParam['P_ANSWER_STATUS']          = _elm( $requests, 's_answer_status' );
        }

        if( empty( _elm( $requests , 's_start_date' ) ) === false && empty( _elm( $requests , 's_end_date' ) ) === false)
        {
            $modelParam['N_START']                  = _elm( $requests, 's_start_date' );
            $modelParam['N_END']                    = _elm( $requests, 's_end_date' );
        }


        if( empty( _elm( $requests, 's_is_notice' ) ) === false ){
            $modelParam['P_NOTI']                   = _elm( $requests, 's_is_notice' );
        }
        if( empty( _elm( $requests, 's_is_stay' ) ) === false ){
            $modelParam['P_STAY']                   = _elm( $requests, 's_is_stay' );
        }



        $modelParam['order']                        = 'P_STAY ASC, P_CREATE_AT DESC';

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $this->boardModel->getPostsLists($modelParam);
        if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){
            foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $lists ){
                #------------------------------------------------------------------
                # TODO: 첨부파일 여부
                #------------------------------------------------------------------
                $files                              = $this->boardModel->getPostsFiles( _elm( $lists, 'P_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['files'] = $files;

                #------------------------------------------------------------------
                # TODO: 댓글 cnt
                #------------------------------------------------------------------
                $comments                           = $this->boardModel->getPostsCommentsCnt( _elm( $lists, 'P_IDX' ) );
                $aLISTS_RESULT['lists'][$aKey]['commentCnt'] = $comments;

                #------------------------------------------------------------------
                # TODO: 게시글 카테고리
                #------------------------------------------------------------------
                $categoryLabel                      = $this->getCategoryLabel( _elm( $lists, 'P_CATE_CODE' ) );
                if( empty( _elm( $lists, 'P_CATE_CODE' ) ) === false ){
                    $categoryLabel                   = $this->getCategoryLabel( _elm( $lists, 'P_CATE_CODE' ) );
                    $aLISTS_RESULT['lists'][$aKey]['categoryLabel'] = $categoryLabel;
                }
                if( empty( _elm( $lists, 'P_SUB_CATE_CODE' ) ) === false ){
                    $subCategoryLabel                   = $this->getCategoryLabel( _elm( $lists, 'P_CATE_CODE' ), _elm( $lists, 'P_SUB_CATE_CODE' ) );
                    $aLISTS_RESULT['lists'][$aKey]['subCategoryLabel'] = $subCategoryLabel;
                }




            }
        }


        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            #------------------------------------------------------------------
            # TODO:  서브뷰 처리
            #------------------------------------------------------------------
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $this->sharedConfig::$board;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;
            $view_datas['bo_id']                    = _elm( $requests, 'bo_id' );

            $this->owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\board\Views\posts\lists_row' , ['owensView' => $this->owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/board' ), '/');
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
    public function getCategoryLabel($pCateCode, $pSubCateCode = null)
    {
        $aConfig                                    = $this->sharedConfig::$board;



        foreach ($aConfig['categories'] as $category) {

            if ( _elm( $category,'value' ) === $pCateCode) {
                // P_CATE_CODE에 해당하는 카테고리 라벨 반환
                if ($pSubCateCode === null) {
                    return _elm( $category, 'label' );
                }

                // P_SUB_CATE_CODE가 있을 경우 서브카테고리에서 라벨 찾기
                foreach ( _elm( $category, 'sub' ) as $subCategory) {
                    if ( _elm( $subCategory, 'value' ) === $pSubCateCode) {
                        return _elm( $subCategory, 'label' );
                    }
                }
                break;
            }
        }

        return null; // 해당하는 라벨이 없을 경우
    }


    public function postsRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();



        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;
        $view_datas['aConfig']                      = $aConfig;
        $view_datas['bo_id']                        = _elm( $requests, 'b_id' );

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\posts\_register' , ['owensView' => $this->owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function postsRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        // print_R( $requests );
        // print_r( $files );
        // print_R( $this->session->get('_memberInfo')  );
        //die;
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
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
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
        $modelParam['P_B_ID']                       = _elm( $requests, 'bo_id' );
        $modelParam['P_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['P_CONTENT']                    = _elm( $requests, 'i_content' );
        $modelParam['P_STAY']                       = _elm( $requests, 'i_is_stay') ?? 'N';
        $modelParam['P_NOTI']                       = _elm( $requests, 'i_is_notice') ?? 'N';
        $modelParam['P_WRITER_IDX']                 = _elm( $this->session->get('_memberInfo') , 'member_idx' );
        $modelParam['P_WRITER_NAME']                = _elm( $this->session->get('_memberInfo') , 'member_name' );
        $modelParam['P_WRITER_GBN']                 = 'admin';
        $modelParam['P_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['P_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['P_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['P_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );



        $this->db->transBegin();
        $aIdx                                       = $this->boardModel->insertPosts( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if( empty( $files ) === false ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'path' => 'board/'._elm( $requests, 'bo_id' ).'/'.$aIdx,
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $key                                    = 0;
            foreach( _elm( $files, 'files' ) as $file){
                if ( $file->getSize() > 0 ) {
                    // 해당 파일이 있을 경우, DB 필드명에 파일 이름을 매칭

                    $file_return                    = $this->_upload( $file, $config );
                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['messages']       = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------
                    $fileParam                      = [];
                    $fileParam['F_P_IDX']           = $aIdx;
                    $fileParam['F_SORT']            = $key + 1;
                    $fileParam['F_PATH']            = _elm( $file_return, 'uploaded_path');
                    $fileParam['F_NAME']            = _elm( $file_return, 'org_name');
                    $fileParam['F_TYPE']            = _elm( $file_return, 'type');
                    $fileParam['F_SIZE']            = _elm( $file_return, 'size');
                    $fileParam['F_EXT']             = _elm( $file_return, 'ext');
                    $fileParam['F_CREATE_AT']       = date( 'Y-m-d H:i:s' );
                    $fileParam['F_CREATE_IP']       = $this->request->getIPAddress();
                    $fileParam['F_CREATE_MB_IDX']   = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                    $bIdx                           = $this->boardModel->setFileDatas( $fileParam );

                    #------------------------------------------------------------------
                    # TODO: DB처리 실패 시
                    #------------------------------------------------------------------
                    if ( $this->db->transStatus() === false || $bIdx === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['messages']       = '처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response, 400 );
                    }
                    $key ++;
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = _elm( $requests, 'bo_id' ).' 게시물 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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

    public function postsDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'n_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $view_datas                                 = [];
        $aConfig                                    = $this->sharedConfig::$board;

        $view_datas['aConfig']                      = $aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getPostsDataByIdx( _elm( $requests, 'n_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        $files                                      = $this->boardModel->getPostsFilesData( _elm( $requests, 'n_idx' ) );
        $aData['files']                             = $files;
        if( empty( _elm( $aData, 'P_EXP_FIELD' ) ) === false ){
            $orderInfo                              = $this->getUserOrderInfo( _elm( $aData, 'P_EXP_FIELD' ) );
            $aData['odrInfo']                       = $orderInfo;

        }

        $view_datas['aData']                        = $aData;
        $view_datas['bo_id']                        = _elm( $aData, 'P_B_ID' );
        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $this->owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\board\Views\posts\_detail' , ['owensView' => $this->owensView] );

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function postsDetailProc()
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
            'i_idx' => [
                'label'  => '게시판 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 IDX누락',
                ],
            ],
            'i_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목을 입력하세요.',
                ],
            ],
            'i_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용을 입력하세요.',
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
        $aData                                      = $this->boardModel->getPostsDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['P_B_ID']                       = _elm( $requests, 'bo_id' );
        $modelParam['P_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['P_CONTENT']                    = _elm( $requests, 'i_content' );
        $modelParam['P_STAY']                       = _elm( $requests, 'i_is_stay') ?? 'N';
        $modelParam['P_NOTI']                       = _elm( $requests, 'i_is_notice') ?? 'N';
        $modelParam['P_STATUS']                     = _elm( $requests, 'i_status' );

        if( _elm( $requests, 'bo_id' ) == 'QNA' ){
            $modelParam['P_ANSWER_STATUS']          = _elm( $requests, 'i_answer_status' );
            if( empty( _elm( $requests, 'i_answer' ) ) === false ){
                $modelParam['P_ANSWER']             = _elm( $requests, 'i_answer' );

                if( _elm( $requests, 'i_answer_status' ) == 'COMPLETED' ){
                    $modelParam['P_ANSWER_AT']      = date( 'Y-m-d H:i:s' );
                    $modelParam['P_ANSWER_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                }
            }
            #------------------------------------------------------------------
            # TODO: 알림톡 보내기. 답변 상태에 따른 메시지
            #------------------------------------------------------------------
            $memberInfo                             = $this->memberModel->getMembershipDataByIdx( _elm( $aData, 'P_WRITER_IDX' ) );
            if( empty( $memberInfo ) === false ){
                $aConfig                            = $this->sharedConfig::$board;
                if( _elm( $aData, 'P_ANSWER_STATUS' ) != _elm( $requests, 'i_answer_status' ) ){
                    $kakaoParam                     = [];
                    $kakaoParam['temp_name']        = '1:1문의 상태변경';
                    $kakaoParam['mobile_num']       = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
                    $kakaoParam['fields']           = [];
                    $kakaoParam['fields']['mall_name']  = '산수유람';
                    $kakaoParam['fields']['writer'] = _elm( $aData, 'P_WRITER_NAME' );
                    $kakaoParam['fields']['short_url']  = shop_url().'mypage/inquiry';

                    if( _elm( $requests, 'i_answer_status' ) == 'RECEIVED' ){
                        $kakaoParam['fields']['status_value']= '문의 접수 로';
                    }else if( _elm( $requests, 'i_answer_status' ) == 'PREPARING' ){
                        $kakaoParam['fields']['status_value']= '답변 준비중 으로';
                    }else if( _elm( $requests, 'i_answer_status' ) == 'COMPLETED' ){
                        $kakaoParam['temp_name']            = '1:1문의';
                        $this->pushSms( $kakaoParam );
                    }
                    if( empty( _elm( _elm( $kakaoParam, 'fields' ), 'status_value' ) ) === false ){
                        $this->pushSms( $kakaoParam );
                    }

                }
            }
        }

        if( _elm( $requests, 'bo_id' ) != 'QNA' ){
            $modelParam['P_UPDATE_AT']              = date( 'Y-m-d H:i:s' );
            $modelParam['P_UPDATE_IP']              = $this->request->getIPAddress();
            $modelParam['P_UPDATE_MB_IDX']          = _elm( $this->session->get('_memberInfo') , 'member_idx' );
        }
        $this->db->transBegin();
        $aStatus                                       = $this->boardModel->updatePosts( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        if( empty( $files ) === false ){
            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------
            $config                                 = [
                'board/'._elm( $requests, 'bo_id' ).'/'._elm( $requests, 'i_idx' ),
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];
            $key                                    = 0;
            foreach( _elm( $files, 'files' ) as $file){
                if ( $file->getSize() > 0 ) {
                    // 해당 파일이 있을 경우, DB 필드명에 파일 이름을 매칭

                    $file_return                    = $this->_upload( $file, $config );
                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['messages']       = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------
                    $fileParam                      = [];
                    $fileParam['F_P_IDX']           = _elm( $requests, 'i_idx' );
                    $fileParam['F_SORT']            = $key + 1;
                    $fileParam['F_PATH']            = _elm( $file_return, 'uploaded_path');
                    $fileParam['F_NAME']            = _elm( $file_return, 'org_name');
                    $fileParam['F_TYPE']            = _elm( $file_return, 'type');
                    $fileParam['F_SIZE']            = _elm( $file_return, 'size');
                    $fileParam['F_EXT']             = _elm( $file_return, 'ext');
                    $fileParam['F_CREATE_AT']       = date( 'Y-m-d H:i:s' );
                    $fileParam['F_CREATE_IP']       = $this->request->getIPAddress();
                    $fileParam['F_CREATE_MB_IDX']   = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                    $bIdx                           = $this->boardModel->setFileDatas( $fileParam );

                    #------------------------------------------------------------------
                    # TODO: DB처리 실패 시
                    #------------------------------------------------------------------
                    if ( $this->db->transStatus() === false || $bIdx === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['messages']       = '처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response, 400 );
                    }
                    $key ++;
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             =  _elm( $requests, 'bo_id' ).' 게시물 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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

    public function changePostsAnswerStatus()
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
            'i_idx' => [
                'label'  => '게시물 번호',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시물 번호 누락.',
                ],
            ],
            'i_answer_status' => [
                'label'  => '답변상태',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '답변상태 누락.',
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
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $this->boardModel->getPostsDataByIdx( _elm( $requests, 'i_idx' ) );

        $modelParam                                 = [];
        $modelParam['P_IDX']                        = _elm( $requests, 'i_idx' );
        $modelParam['P_ANSWER_STATUS']              = _elm( $requests, 'i_answer_status' );

        $this->db->transBegin();
        $aStatus                                    = $this->boardModel->updatePosts( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상태 변경 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 알림톡 보내기. 답변 상태에 따른 메시지
        #------------------------------------------------------------------
        $memberInfo                             = $this->memberModel->getMembershipDataByIdx( _elm( $aData, 'P_WRITER_IDX' ) );
        if( empty( $memberInfo ) === false ){
            $aConfig                            = $this->sharedConfig::$board;
            if( _elm( $aData, 'P_ANSWER_STATUS' ) != _elm( $requests, 'i_answer_status' ) ){
                $kakaoParam                     = [];
                $kakaoParam['temp_name']        = '1:1문의 상태변경';
                $kakaoParam['mobile_num']       = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
                $kakaoParam['fields']           = [];
                $kakaoParam['fields']['mall_name']  = '산수유람';
                $kakaoParam['fields']['writer'] = _elm( $aData, 'P_WRITER_NAME' );
                $kakaoParam['fields']['short_url']  = shop_url().'mypage/inquiry';

                if( _elm( $requests, 'i_answer_status' ) == 'RECEIVED' ){
                    $kakaoParam['fields']['status_value']= '문의 접수 로';
                }else if( _elm( $requests, 'i_answer_status' ) == 'PREPARING' ){
                    $kakaoParam['fields']['status_value']= '답변 준비중 으로';
                }
                if( empty( _elm( _elm( $kakaoParam, 'fields' ), 'status_value' ) ) === false ){
                    $this->pushSms( $kakaoParam );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             =  _elm( $requests, 'bo_id' ).' 게시물 답변 상태변경 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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
        $response['alert']                          = '변경 되었습니다.';

        return $this->respond( $response );
    }
    public function deletePostsFile()
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
            'file_idx' => [
                'label'  => 'file_idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '파일 idx 값 누락',
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
        $aData                                      = $this->boardModel->getPostsFileDataByIdx( _elm( $requests, 'file_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다.';

            return $this->respond( $response );
        }

        $this->db->transBegin();
        $aStatus                                    = $this->boardModel->deletePostsFile( _elm( $requests, 'file_idx' ) );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제처리
        #------------------------------------------------------------------
        $finalFilePath                              = WRITEPATH . _elm( $aData, 'F_PATH' );
        if (file_exists($finalFilePath)) {
            @unlink($finalFilePath);
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             =  ' 게시물 파일 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );
    }

    public function getUserOrderInfo( $ordInfo )
    {
        $response                                   = $this->_initApiResponse();

        if( empty( $ordInfo ) === true ){
            $response['status']                     = 400;
            $response['messages']                   = '주문정보 없습니다.';
            return $response;
            exit;
        }
        $odrInfo                                    = json_decode( $ordInfo, true );

        $returnData                                 = [];

        foreach( $odrInfo as $oKey => $info ){
            $ordNo                                  = _elm( $info, 'orderNum' );

            $orderInfo                              = $this->orderModel->getUserOrderDataByOrdNo( $ordNo );
            if(  empty( $orderInfo ) === true){
                $response['status']                 = 400;
                $response['messages']               = '주문 데이터가 없습니다.';
                return $response;
                exit;
            }


            $goodsIdxs                              = explode( ',', _trim( _elm( $orderInfo, 'PRODUCT_IDX' ) ) );
            $optionNames                            = explode( ',', _trim( _elm( $orderInfo, 'OPTION_NAMES' ) ) );
            $optionQtys                             = explode( ',', _trim( _elm( $orderInfo, 'P_QTYS' ) ) );
            $returnData[$oKey]['orderNum']          = _elm( $orderInfo, 'O_ORDID' );
            $returnData[$oKey]['orderAt']           = date( 'Y-m-d', strtotime( _elm( $orderInfo, 'O_ORDER_AT' ) ) );

            if( empty( $goodsIdxs ) === false ){
                $n                                  = 0;
                foreach( $goodsIdxs as $goodsIdx ){
                    if( in_array( $goodsIdx, _elm( $info, 'orderProduct' ) ) ){

                        $goodsInfo                  = $this->goodsModel->getGoodsDataByIdx( trim( $goodsIdx ) );
                        $_rDt                       = [];
                        $_rDt['pGoodsIdx']          = _elm( $goodsInfo, 'G_IDX' );
                        $_rDt['pGoodsNm']           = _elm( $goodsInfo, 'G_NAME' );
                        $_rDt['pGoodsNmEng']        = _elm( $goodsInfo, 'G_NAME_ENG' );
                        $_rDt['pSellPrice']         = _elm( $goodsInfo, 'G_SELL_PRICE' );
                        $_rDt['pPrice']             = _elm( $goodsInfo, 'G_PRICE' );
                        $_rDt['pTotStock']          = _elm( $goodsInfo, 'G_STOCK_CNT' );
                        $brandInfo                  = $this->brandModel->getBrandDataByIdx( _elm( $goodsInfo, 'G_BRAND_IDX' ) );
                        $_rDt['pBrandName']         = _elm( $brandInfo, 'C_BRAND_NAME' );
                        $_rDt['pBrandNameEng']      = _elm( $brandInfo, 'C_BRAND_NAME_ENG' );
                        $_rDt['pMakerName']         = _elm( $goodsInfo, 'G_MAKER_NAME' );
                        // $_rDt['pFavoritesCnt']      = _elm( $goodsInfo, 'FAVORITES_CNT', '0', true );
                        // $_rDt['pStarAvg']           = _elm( $goodsInfo, 'STAR_AVG', '0' );
                        // $_rDt['pReviewCnt']         = _elm( $goodsInfo, 'REVIEW_CNT', '0' );
                        // $_rDt['pFavorites']         = 'N';
                        $_rDt['pOptionName']        = _elm( $optionNames, $n );
                        $_rDt['pOptionQty']         = _elm( $optionQtys, $n );

                        #------------------------------------------------------------------
                        # TODO: 상품 이미지 로드
                        #------------------------------------------------------------------
                        $_imgDatas                  = $this->goodsModel->getGoodsInImages( _elm( $goodsInfo, 'G_IDX' ));
                        $imgDatas                   = [];
                        if( !empty( $_imgDatas ) ){
                            foreach ($_imgDatas as $imgData) {
                                $viewSize = empty( _elm( $imgData, 'I_IMG_VIEW_SIZE' ) ) == true ? 'origin' : _elm( $imgData, 'I_IMG_VIEW_SIZE' ) ; // I_IMG_VIEW_SIZE가 없는 경우 'default'로 설정
                                if( $viewSize == '250' ){
                                    $imgDatas[$viewSize][]  = $imgData;
                                }
                            }
                        }
                        $_rDt['images']             = $imgDatas;

                        #------------------------------------------------------------------
                        # TODO: 상품 아이콘 로드
                        #------------------------------------------------------------------
                        // $_iconsDatas                = $this->iconsModel->getGoodsInIcons( _elm( $goodsInfo, 'G_IDX' ));

                        // $iconsDatas                 = [];
                        // if( !empty( $_iconsDatas ) ){
                        //     foreach ($_iconsDatas as $iconsData) {
                        //         $iconsDatas[]       = $iconsData;
                        //     }
                        // }
                        // $_rDt['iconsInfo']          = $iconsDatas;

                        $returnData[$oKey]['orderProduct'][]       = $_rDt;
                        $n++;
                    }

                }
            }
        }




        $response['status']                         = 200;
        $response['returnData']                     = $returnData;

        return $response;
    }



}

