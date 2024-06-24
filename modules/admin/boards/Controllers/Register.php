<?php
#------------------------------------------------------------------
# Register.php
# 시스템적인 등록 관련 컨트롤러
# 김우진
# 2024-05-22 16:19:43
# @Desc :
#------------------------------------------------------------------

namespace Module\admin\boards\Controllers;

use Module\admin\boards\Controllers\Boards;


use Config\Services;
use DOMDocument;
use Module\admin\boards\Models\DeleteModel;

class Register extends Boards
{


    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function addBoards()
    {

        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_group' => [
                'label'  => '그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '그룹값 누락',
                ],
            ],
            'u_id' => [
                'label'  => '게시판명',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[15]',
                'errors' => [
                    'required' => '게시판명값 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = $validation->getErrors();

            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam                                         = [];
        $modelParam['B_GROUP']                              = _elm( $requests, 'u_group' );
        $modelParam['B_ID']                                 = _elm( $requests, 'u_id' );
        $modelParam['B_STATUS']                             = 1;

        #------------------------------------------------------------------
        # TODO: 카테고리 검사
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_category_code' ) ) === false ){
            $modelParam['B_CATEGORY_CODE']                  = implode( "|", _elm( $requests, 'u_category_code' ) );
        }

        #------------------------------------------------------------------
        # TODO: option setting
        #------------------------------------------------------------------
        $modelParam['B_TITLE']                              = _elm( $requests, 'u_title' );

        $modelParam['B_HITS']                               = _elm( $requests, 'u_hits', 'Y' , true );
        $modelParam['B_SECRET']                             = _elm( $requests, 'u_secret', 'Y' , true );
        $modelParam['B_PASSWORD']                           = _elm( $requests, 'u_password', '4' , true );
        $modelParam['B_COMMENT']                            = _elm( $requests, 'u_comment', 'Y' , true );
        $modelParam['B_IS_FREE']                            = _elm( $requests, 'u_is_free', 'N' , true );
        $modelParam['B_CREATE_AT']                          = date('Y-m-d H:i:s');
        $modelParam['B_CREATE_IP']                          = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['B_CREATE_MB_IDX']                      = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        #------------------------------------------------------------------
        # TODO: 게시판 중복체크
        #------------------------------------------------------------------
        $sameChecked                                        = $this->boardsModel->sameChecked( $modelParam );

        if( $sameChecked > 0 ){
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = '중복된 게시판명입니다.';

            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 정렬순서 자동지정
        #------------------------------------------------------------------
        $sort                                               = $this->boardsModel->getLastBoardSortNum( $modelParam );

        $modelParam['B_SORT']                               = $sort + 1 ?? 1;
        #------------------------------------------------------------------
        # TODO: 데이터 등록
        #------------------------------------------------------------------

        $this->db->transBegin();

        $mIdx                                               = $this->registerModel->setBoardInfo( $modelParam );

        if ( $this->db->transStatus() === false || $mIdx === false ) {
            $this->db->transRollback();
            $response['status']                              = 400;
            $response['messages']                            = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = '게시판 등록 - 게시판명: '._elm( $modelParam, 'B_TITLE' );
        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        $this->db->transCommit();

        $response['status']                                  = 200;
        $response['messages']                                = 'success';


        return $this->respond( $response );
    }



    public function modBoards()
    {
        $response                                       = $this->_initApiResponse();
        $requests                                       = _parseJsonFormData();


        $validation                                     = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                         = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_idx' => [
                'label'  => '게시판IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판IDX값 누락',
                ],
            ],
            'u_group' => [
                'label'  => '그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '그룹값 누락',
                ],
            ],
            'u_id' => [
                'label'  => '게시판명',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[15]',
                'errors' => [
                    'required' => '게시판명값 누락',
                ],
            ],

        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                            = 400;
            $response['error']                             = 400;
            $response['messages']                          = $validation->getErrors();

            return $this->respond($response, 400);
        }
        $modelParam                                        = [];
        $modelParam['B_IDX']                               = _elm( $requests, 'u_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $aData                                             = $this->boardsModel->getBoardsInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                            = 400;
            $response['error']                             = 400;
            $response['messages']                          = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------


        $modelParam['B_GROUP']                              = _elm( $requests, 'u_group' );
        $modelParam['B_ID']                                 = _elm( $requests, 'u_id' );
        $modelParam['B_STATUS']                             = _elm( $requests, 'u_status' );

        #------------------------------------------------------------------
        # TODO: 카테고리 검사
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_category_code' ) ) === false ){
            $chkCateValue                                   = implode( "|", _elm( $requests, 'u_category_code' ) );
            if( _elm( $aData, 'B_CATEGORY_CODE' ) != $chkCateValue ){
                $modelParam['B_CATEGORY_CODE']              = $chkCateValue;
            }
        }

        #------------------------------------------------------------------
        # TODO: option setting
        #------------------------------------------------------------------
        $modelParam['B_TITLE']                              = _elm( $requests, 'u_title' );

        $modelParam['B_HITS']                               = _elm( $requests, 'u_hits', 'Y' , true );
        $modelParam['B_SECRET']                             = _elm( $requests, 'u_secret', 'Y' , true );
        $modelParam['B_PASSWORD']                           = _elm( $requests, 'u_password', '4' , true );
        $modelParam['B_COMMENT']                            = _elm( $requests, 'u_comment', 'Y' , true );
        $modelParam['B_IS_FREE']                            = _elm( $requests, 'u_is_free', 'N' , true );

        $modelParam['B_MODIFY_AT']                          = date('Y-m-d H:i:s');
        $modelParam['B_MODIFY_IP']                          = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['B_MODIFY_MB_IDX']                      = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );


        #------------------------------------------------------------------
        # TODO: 게시판명이 바뀌면
        #------------------------------------------------------------------
        if( _elm( $aData, 'B_TITLE' ) != _elm( $modelParam, 'B_TITLE' ) ){
            #------------------------------------------------------------------
            # TODO: 게시판 중복체크
            #------------------------------------------------------------------
            $sameChecked                                    = $this->boardsModel->sameChecked( $modelParam );

            if( $sameChecked > 0 ){
                $response['status']                         = 400;
                $response['error']                          = 400;
                $response['messages']                       = '중복된 게시판명입니다.';

                return $this->respond( $response, 400 );
            }
        }

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 정렬 순서가 틀리면 재정렬
        #------------------------------------------------------------------
        if( _elm( $requests, 'u_sort' ) != _elm( $aData, 'B_SROT' ) ){
            $modelParam['B_SORT']                           = _elm( $requests, 'u_sort' );

            $this->boardsModel->reSort( _elm( $modelParam, 'B_GROUP' ), _elm( $aData, 'B_SORT' ), _elm( $modelParam, 'B_SORT' ) );

        }

        #------------------------------------------------------------------
        # TODO: 데이터 수정
        #------------------------------------------------------------------

        $mStatus                                             = $this->registerModel->updateBoardInfo( $modelParam );

        if ( $this->db->transStatus() === false || $mStatus === false ) {
            $this->db->transRollback();
            $response['status']                              = 400;
            $response['messages']                            = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = '게시판 수정 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL."=>newData:".json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------



        $this->db->transCommit();

        $response['status']                                  = 200;
        $response['messages']                                = 'success';


        return $this->respond( $response );
    }


    public function postsRegister( $param = [] )
    {
        $response                                  = $this->_initApiResponse();

        $requests                                  = _parseJsonFormData();

        $files                                     = $this->request->getFiles();

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $setRules                                  = [
            'u_board_id' => [
                'label'  => '게시판ID',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[15]',
                'errors' => [
                    'required' => '게시판 ID값 누락',
                ],
            ],

        ];
        $validation->setRules(
            $setRules
        );



        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }
        $modelParam                                = [];
        $modelParam['B_ID']                        = _elm( $requests, 'u_board_id' );
        $boardConfig                               = $this->boardsModel->getBoardsInfoById( $modelParam );

        if( empty( $boardConfig ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '존재하지 않는 게시판입니다. 게시판 아이디를 확인해주세요.';

            return $this->respond( $response );
        }

        $setRules                                  = [
            'u_category_code' => [
                'label'  => '게시판 카테고리',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 카테고리값 누락',
                ],
            ],
            'u_title' => [
                'label'  => '제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '제목 누락',
                ],
            ],
            'u_content' => [
                'label'  => '내용',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '내용 누락',
                ],
            ]
        ];

        #------------------------------------------------------------------
        # TODO: 설정에 따른 값 확인 및 검수
        #------------------------------------------------------------------
        if( _elm( $boardConfig, 'B_SECRET' ) == 'Y' ){
            if( empty( _elm( $requests, 'u_secret' ) ) === true ){
                $requests['u_secret']              = 'N';
            }
            if( _elm( $requests, 'u_secret' ) === 'Y' ){
                #------------------------------------------------------------------
                # TODO: 비밀글일때 패스워드 검수.
                #------------------------------------------------------------------
                $setRules += [
                        'u_password' => [
                        'label'  => '비밀번호',
                        'rules'  => 'trim|required|max_length['._elm( $boardConfig, 'B_PASSWORD').']|min_length['._elm( $boardConfig, 'B_PASSWORD').']',
                        'errors' => [
                            'required' => '비밀번호 값 누락',
                        ],
                    ],
                ];
            }

        }

        $validation->setRules(
            $setRules
        );
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 게시판에 들어가는 카테고리가 아닐 경우 에러
        #------------------------------------------------------------------

        $categoryCodes                             = explode('|', _elm($boardConfig, 'B_CATEGORY_CODE'));
        $categoryCode                              = _elm($requests, 'u_category_code');

        if (!in_array($categoryCode, $categoryCodes)) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '카테고리 오류 다시 시도해주세요.';
            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 금칙어 검수
        #------------------------------------------------------------------
        $banned_title                              = contains_banned_words( _elm( $requests, 'u_title' ) );
        if( $banned_title ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '제목에 금칙어가 있습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        $banned_content                            = contains_banned_words( _elm( $requests, 'u_content' ) );
        if( $banned_content ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '내용에 금칙어가 있습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $modelParam                                = [];
        $modelParam['P_B_ID']                      = _elm( $requests, 'u_board_id' );
        $modelParam['P_C_CODE']                    = _elm( $requests, 'u_category_code' );

        $modelParam['P_WRITER_IDX']                = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        #------------------------------------------------------------------
        # TODO: 공지 및 상단고정
        #------------------------------------------------------------------
        $modelParam['P_NOTI']                      = _elm( $requests, 'u_noti', null, true ) ?? 0 ;
        $modelParam['P_STAY']                      = _elm( $requests, 'u_stay', null, true ) ?? 0 ;

        #------------------------------------------------------------------
        # TODO: 상태값 기본 1
        #------------------------------------------------------------------
        $modelParam['P_STATUS']                    = 1;

        $modelParam['P_LINK_URL']                  = _elm( $requests, 'u_link_url', null, true ) ?? '' ;
        $modelParam['P_TITLE']                     = _elm( $requests, 'u_title' );
        $modelParam['P_CONTENT']                   = htmlspecialchars( _elm( $requests, 'u_content' ) );
        $modelParam['P_SECRET']                    = _elm( $requests, 'u_secret' );

        #------------------------------------------------------------------
        # TODO:  비밀글 일때 비밀번호 인코딩
        #------------------------------------------------------------------
        $modelParam['P_PASSWORD']                  = _elm( $requests, 'u_secret' ) === 'Y'? $this->_aesEncrypt( _elm( $requests, 'u_password') ): null;

        #------------------------------------------------------------------
        # TODO: 글 노출 기간 있을때 설정
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_start_date' ) ) === false && empty( _elm( $requests, 'u_end_date' ) ) === false ){
            $modelParam['P_START_DATE']            = date( 'Y-m-d', strtotime( _elm( $requests, 'u_start_date' ) ) );
            $modelParam['P_END_DATE']              = date( 'Y-m-d', strtotime( _elm( $requests, 'u_end_date' ) ) );
        }

        $modelParam['P_CREATE_AT']                 = date( 'Y-m-d H:i:s' );
        $modelParam['P_CREATE_IP']                 = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['P_CREATE_MB_IDX']             = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 게시물 등록
        #------------------------------------------------------------------
        $mIdx                                      = $this->registerModel->registerPosts( $modelParam );

        if ( $this->db->transStatus() === false || $mIdx === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------

        if( empty( $files ) === false ){

            $config                                = [
                'path' => _elm( $requests, 'u_board_id' ).'/'.$mIdx,
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( _elm( $files, 'file' ) as $key => $file ){

                $file_return                       = $this->_upload( $file, $config );
                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = _elm( $file_return, 'error' );
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 데이터모델 세팅
                #------------------------------------------------------------------
                $fileParam                         = [];
                $fileParam['F_P_IDX']              = $mIdx;
                $fileParam['F_SORT']               = $key + 1;
                $fileParam['F_PATH']               = _elm( $file_return, 'uploaded_path');
                $fileParam['F_NAME']               = _elm( $file_return, 'org_name');
                $fileParam['F_TYPE']               = _elm( $file_return, 'type');
                $fileParam['F_SIZE']               = _elm( $file_return, 'size');
                $fileParam['F_EXT']                = _elm( $file_return, 'ext');
                $fileParam['F_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                $fileParam['F_CREATE_IP']          = _elm( $_SERVER, 'REMOTE_ADDR' );
                $fileParam['F_CREATE_MB_IDX']      = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

                $bIdx                              = $this->registerModel->setFileDatas( $fileParam );

                #------------------------------------------------------------------
                # TODO: DB처리 실패 시
                #------------------------------------------------------------------
                if ( $this->db->transStatus() === false || $bIdx === false ) {
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = '처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response, 400 );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 등록 -  B_IDX: '.$mIdx.' => data: '.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond( $response );

    }

    public function postsModify()
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        // $_FILES 배열 초기화 및 설정
        $_FILES = [];
        foreach ($requests as $key => $value) {
            if (strpos($key, 'file[') !== false) {
                $index = str_replace(['file[', ']'], '', $key);
                $_FILES['file']['name'][$index]    = $value['name'];
                $_FILES['file']['type'][$index]    = $value['type'];
                $_FILES['file']['tmp_name'][$index]= $value['tmp_name'];
                $_FILES['file']['error'][$index]   = $value['error'];
                $_FILES['file']['size'][$index]    = $value['size'];
            }
        }

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $setRules = [
            'u_board_id' => [
                'label' => '게시판ID',
                'rules' => 'trim|required|alpha_custom|min_length[1]|max_length[30]',
                'errors' => [
                    'required' => '게시판 ID값 누락',
                ],
            ],
            'u_idx' => [
                'label' => '게시글 IDX',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '게시글 IDX 누락',
                ],
            ],
        ];
        $validation->setRules($setRules);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ($isRule === true && $validation->run($requests) === false) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $modelParam = [];
        #------------------------------------------------------------------
        # TODO: 게시판 유무 확인
        #------------------------------------------------------------------
        $modelParam['B_ID']                        = _elm($requests, 'u_board_id');
        $boardConfig                               = $this->boardsModel->getBoardsInfoById($modelParam);

        if (empty($boardConfig) === true) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '존재하지 않는 게시판입니다. 게시판 아이디를 확인해주세요.';

            return $this->respond($response);
        }
        unset($modelParam['B_ID']);

        #------------------------------------------------------------------
        # TODO: 게시글 유무 확인
        #------------------------------------------------------------------
        $modelParam['P_IDX']                       = _elm($requests, 'u_idx');
        $aData                                     = $this->boardsModel->getPostsInfo($modelParam);

        if (empty($aData) === true) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '게시물이 존재하지 않습니다. 다시 시도해주세요.';

            return $this->respond($response);
        }

        $aData['fileInfo']                         = $this->boardsModel->getPostsInFiles($modelParam);

        $setRules = [
            'u_category_code' => [
                'label' => '게시판 카테고리',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '게시판 카테고리값 누락',
                ],
            ],
            'u_title' => [
                'label' => '제목',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '제목 누락',
                ],
            ],
            'u_content' => [
                'label' => '내용',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '내용 누락',
                ],
            ],
        ];

        $validation->setRules($setRules);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ($isRule === true && $validation->run($requests) === false) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 금칙어 검수
        #------------------------------------------------------------------
        $banned_title = contains_banned_words(_elm($requests, 'u_title'));
        if ($banned_title) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '제목에 금칙어가 있습니다. 다시 시도해주세요.';

            return $this->respond($response);
        }

        $banned_content = contains_banned_words(_elm($requests, 'u_content'));
        if ($banned_content) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '내용에 금칙어가 있습니다. 다시 시도해주세요.';

            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: 게시판에 들어가는 카테고리가 아닐 경우 에러
        #------------------------------------------------------------------
        $categoryCodes                             = explode('|', _elm($boardConfig, 'B_CATEGORY_CODE'));
        $categoryCode                              = _elm($requests, 'u_category_code');

        if (!in_array($categoryCode, $categoryCodes)) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '카테고리 오류 다시 시도해주세요.';
            return $this->respond($response, 400);
        }

        $modelParam['P_B_ID']                      = _elm($requests, 'u_board_id');
        $modelParam['P_C_CODE']                    = _elm($requests, 'u_category_code');
        $modelParam['P_WRITER_IDX']                = _elm(_elm($GLOBALS, 'userInfo'), 'MB_IDX');

        #------------------------------------------------------------------
        # TODO: 공지 및 상단고정
        #------------------------------------------------------------------
        $modelParam['P_NOTI']                      = _elm($requests, 'u_noti', null, true) ?? 'N';
        $modelParam['P_STAY']                      = _elm($requests, 'u_stay', null, true) ?? 'N';

        #------------------------------------------------------------------
        # TODO: 상태값 기본 1
        #------------------------------------------------------------------
        if (empty(_elm($requests, 'u_status')) === false) {
            $modelParam['P_STATUS']                = _elm($requests, 'u_status');
        }
        if (empty(_elm($requests, 'u_link_url')) === false) {
            $modelParam['P_LINK_URL']              = _elm($requests, 'u_link_url', null, true) ?? '';
        }

        $modelParam['P_TITLE']                     = _elm($requests, 'u_title');
        $modelParam['P_CONTENT']                   = htmlspecialchars(_elm($requests, 'u_content'));

        #------------------------------------------------------------------
        # TODO: 글 노출 기간 있을때 설정
        #------------------------------------------------------------------
        if (empty(_elm($requests, 'u_start_date')) === false && empty(_elm($requests, 'u_end_date')) === false) {
            $modelParam['P_START_DATE']            = date('Y-m-d', strtotime(_elm($requests, 'u_start_date')));
            $modelParam['P_END_DATE']              = date('Y-m-d', strtotime(_elm($requests, 'u_end_date')));
        }

        $modelParam['P_UP_AT']                     = date('Y-m-d H:i:s');
        $modelParam['P_UP_IP']                     = _elm($_SERVER, 'REMOTE_ADDR');
        $modelParam['P_UP_MB_IDX']                 = _elm(_elm($GLOBALS, 'userInfo'), 'MB_IDX');

        #------------------------------------------------------------------
        # TODO: 데이터 수정
        #------------------------------------------------------------------
        $this->db->transBegin();
        $aStatus                                   = $this->registerModel->modifyPosts($modelParam);

        if ($this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '데이터 수정 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        if (!empty($_FILES['file']['name'])) {
            $config                                = [
                'path'                             => _elm($requests, 'u_board_id') . '/' . _elm($requests, 'u_idx'),
                'mimes'                            => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach ($_FILES['file']['name'] as $key => $name) {
                $file                              = [
                    'name'                         => $_FILES['file']['name'][$key],
                    'type'                         => $_FILES['file']['type'][$key],
                    'tmp_name'                     => $_FILES['file']['tmp_name'][$key],
                    'error'                        => $_FILES['file']['error'][$key],
                    'size'                         => $_FILES['file']['size'][$key]
                ];

                $_fileParam                        = [];
                $_fileParam['F_P_IDX']             = _elm($requests, 'u_idx');
                $_fileParam['F_SORT']              = $key + 1;

                $fileData                          = $this->boardsModel->getFileInfo($_fileParam);

                #------------------------------------------------------------------
                # TODO: 파일 삭제처리
                #------------------------------------------------------------------
                if (empty($fileData) === false) {
                    $dStatus                           = $this->deleteModel->deleteFile($_fileParam);
                    if ($this->db->transStatus() === false || $dStatus === false) {
                        $this->db->transRollback();
                        $response['status']            = 400;
                        $response['messages']          = '기존 파일 삭제 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond($response, 400);
                    }

                    if (file_exists(WRITEPATH . _elm($fileData, 'F_PATH'))) {
                        unlink(WRITEPATH . _elm($fileData, 'F_PATH'));
                    }
                }


                $file_return                       = $this->_upload_put($file, $config);

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if (_elm($file_return, 'status') === false) {
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = _elm( $file_return, 'error' );
                    return $this->respond($response, 400);
                }

                #------------------------------------------------------------------
                # TODO: 수정될 데이터모델 세팅
                #------------------------------------------------------------------
                $fileParam = [];
                $fileParam['F_P_IDX']              = _elm($requests, 'u_idx');
                $fileParam['F_SORT']               = _elm($fileData, 'F_SORT');
                $fileParam['F_PATH']               = _elm($file_return, 'uploaded_path');
                $fileParam['F_NAME']               = _elm($file_return, 'org_name');
                $fileParam['F_TYPE']               = _elm($file_return, 'type');
                $fileParam['F_SIZE']               = _elm($file_return, 'size');
                $fileParam['F_EXT']                = _elm($file_return, 'ext');
                $fileParam['F_CREATE_AT']          = date('Y-m-d H:i:s');
                $fileParam['F_CREATE_IP']          = _elm($_SERVER, 'REMOTE_ADDR');
                $fileParam['F_CREATE_MB_IDX']      = _elm(_elm($GLOBALS, 'userInfo'), 'MB_IDX');

                $bStatus                           = $this->registerModel->setFileDatas($fileParam);

                #------------------------------------------------------------------
                # TODO: DB처리 실패 시
                #------------------------------------------------------------------
                if ($this->db->transStatus() === false || $bStatus === false) {
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = '파일 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond($response, 400);
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 수정 -  orgData: ' . json_encode($aData, JSON_UNESCAPED_UNICODE) . ' => newData: ' . json_encode($modelParam, JSON_UNESCAPED_UNICODE);
        $logParam['MB_IDX']                        = _elm(_elm($GLOBALS, 'userInfo'), 'MB_IDX');

        $this->LogModel->insertAdminLog($logParam);

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond($response);
    }


    public function postsModify2 ()
    {
        $response = $this->_initApiResponse();
        $requests = _parseJsonFormData();

        // 수동으로 $_FILES 배열에 파일 데이터 추가
        $_FILES = [];
        foreach ($requests as $key => $value) {
            if (strpos($key, 'file[') !== false) {
                $index = str_replace(['file[', ']'], '', $key);
                $_FILES['file']['name'][$index] = $value['name'];
                $_FILES['file']['type'][$index] = $value['type'];
                $_FILES['file']['tmp_name'][$index] = $value['tmp_name'];
                $_FILES['file']['error'][$index] = $value['error'];
                $_FILES['file']['size'][$index] = $value['size'];
            }
        }

        $uploadDirectory = WRITEPATH . "uploads/test/";
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // 파일이 업로드되었는지 확인
        if (!empty($_FILES['file'])) {
            foreach ($_FILES['file']['name'] as $key => $name) {
                $tmpName = $_FILES['file']['tmp_name'][$key];
                $fileName = basename($name);
                $uploadPath = $uploadDirectory . $fileName;

                // 파일이 유효한지 확인
                if (is_uploaded_file($tmpName) || file_exists($tmpName)) {
                    // 파일 이동
                    if (move_uploaded_file($tmpName, $uploadPath) || rename($tmpName, $uploadPath)) {
                        echo "파일 업로드 성공: $uploadPath";
                    } else {
                        echo "파일 업로드 실패.";
                    }
                } else {
                    echo "파일 업로드 중 오류 발생: " . $_FILES['file']['error'][$key];
                }
            }
        } else {
            echo "업로드된 파일이 없습니다.";
        }

        // 기타 일반 필드 파라미터 처리
        $boardId = _elm($requests, 'u_board_id');
        $postId = _elm($requests, 'u_idx');
        $categoryCode = _elm($requests, 'u_category_code');
        $title = _elm($requests, 'u_title');
        $content = _elm($requests, 'u_content');

        // 나머지 로직 처리
        $response['status'] = 200;
        $response['messages'] = 'success';

        return $this->respond($response);

    }


    public function commentRegister( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        if( empty( $param ) === false ){
            $requests                              = $param;
        }
        // $commParam                                 = [];
        // $commParam['P_IDX']                        = _elm( $requests, 'u_idx' );
        // $comments                                  = $this->boardsModel->getPostsComments( $commParam );
        // $aData['COMMENTS']                         = _build_tree( $comments, 0, 'C_IDX', 'C_PARENT_IDX' );

        // print_r( $aData );
        // die;

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $setRules                                  = [
            'u_board_id' => [
                'label'  => '게시판ID',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[30]',
                'errors' => [
                    'required' => '게시판ID 누락',
                ],
            ],
            'u_idx' => [
                'label'  => '게시글 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시물IDX 누락',
                ],
            ],
            'u_board_id' => [
                'label'  => '게시판ID',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[30]',
                'errors' => [
                    'required' => '게시판ID 누락',
                ],
            ],
        ];
        $validation->setRules($setRules);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $modelParam                                = [];
        $modelParam['B_ID']                        = _elm( $requests, 'u_board_id' );
        $boardConfig                               = $this->boardsModel->getBoardsInfoById( $modelParam );
        if( _elm( $boardConfig, 'B_SECRET' ) == 'Y' ){
            if( empty( _elm( $requests, 'u_secret' ) ) === true ){
                $requests['u_secret']              = 'N';
            }
            if( _elm( $requests, 'u_secret' ) === 'Y' ){
                #------------------------------------------------------------------
                # TODO: 비밀글일때 패스워드 검수.
                #------------------------------------------------------------------
                $setRules += [
                        'u_password' => [
                        'label'  => '비밀번호',
                        'rules'  => 'trim|required|max_length['._elm( $boardConfig, 'B_PASSWORD').']|min_length['._elm( $boardConfig, 'B_PASSWORD').']',
                        'errors' => [
                            'required' => '비밀번호 값 누락',
                        ],
                    ],
                ];
            }

        }

        $validation->setRules($setRules);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO: 금칙어 검수
        #------------------------------------------------------------------

        $banned_comment                            = contains_banned_words( _elm( $requests, 'u_comment' ) );
        if( $banned_comment ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '댓글에 금칙어가 있습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $modelParam                                = [];
        $modelParam['C_B_IDX']                     = _elm( $requests, 'u_idx' );
        $modelParam['C_WRITER_IDX']                = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );
        $modelParam['C_PARENT_IDX']                = _elm( $requests, 'u_parent_idx', 0, true );
        $modelParam['C_DEPTH']                     = _elm( $requests, 'u_depth', 1, true );
        $modelParam['C_STATUS']                    = _elm( $requests, 'u_status', 1, true );
        $modelParam['C_COMMENT']                   = htmlspecialchars( _elm( $requests, 'u_comment' ) );
        $modelParam['C_SECRET']                    = _elm( $requests, 'u_secret' );
        $modelParam['C_PASSWORD']                  = $this->_aesEncrypt( _elm( $requests, 'u_password' ) );
        $modelParam['C_REG_AT']                    = date('Y-m-d :H:i:s');
        $modelParam['C_REG_IP']                    = _elm( $_SERVER, 'REMOTEADDR' );

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 댓글 등록
        #------------------------------------------------------------------

        $mIdx                                      = $this->registerModel->setComment( $modelParam );

        if ( $this->db->transStatus() === false || $mIdx === false ) {
            $this->db->transRollback();
            $response['status']                              = 400;
            $response['messages']                            = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }


        #------------------------------------------------------------------
        # TODO: 등록 후 커맨트 리로드
        #------------------------------------------------------------------
        if( _elm( $boardConfig, 'B_COMMENT' ) == 'Y' ){
            $commParam                                 = [];
            $commParam['P_IDX']                        = _elm( $modelParam, 'C_B_IDX' );
            $comments                                  = $this->boardsModel->getPostsComments( $commParam );

            $aData['COMMENTS']                         = _build_tree( $comments, 0, 'C_IDX', 'C_PARENT_IDX' );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 댓글 입력 -  data: '.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';
        $response['COMMENT']                       = _elm(  $aData , 'COMMENTS' );

        return $this->respond( $response );


    }

    public function upload( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $files                                     = $this->request->getFiles();

        $fileParam                                 = [];


        if( empty( $files ) === true ){

            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '업도르 할 파일이 없습니다.';

            return $this->respond( $response );
        }else{

            $config                                = [
                'path' =>'board/editor',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            $i=0;
            $totalSize                             = 0;
            $fileErrors                            = [];
            #------------------------------------------------------------------
            # TODO: 파일 사이즈 우선 체크
            #------------------------------------------------------------------
            foreach( _elm( $files, 'file' ) as $fileName => $file ){
                if( $file->getSize() >= (10240*1024) ){
                    $fileErrors[]                  = '파일크기 초과 > '.$file->getName().PHP_EOL;
                }
                $totalSize += $file->getSize().PHP_EOL;
            }

            #------------------------------------------------------------------
            # TODO: 에러가 있으면 에러 먼저 리턴
            #------------------------------------------------------------------
            if( empty( $fileErrors ) === false ){
                $response['status']                 = 400;
                $response['error']                  = 400;
                $response['messages']               = join('',$fileErrors);

                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 총 사이즈 합산이 50MB가 넘어가면 에러리턴
            #------------------------------------------------------------------

            if( ( ( 10240 * 10 ) * 1024 ) < $totalSize  ){
                $response['status']                  = 400;
                $response['error']                   = 400;
                $response['messages']                = '용량은 한번에 최대 50MB까지 올릴 수 있습니다.';

                return $this->respond( $response );
            }

            foreach( _elm( $files, 'file' ) as $fileName => $file  ){

                if( $file->getSize() > 0 ){
                    $file_return                       = $this->_upload( $file, $config );

                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']            = 400;
                        $response['messages']          = _elm($file_return , 'error') ;
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 리턴값 세팅
                    #------------------------------------------------------------------
                    $fileParam[$i]['src']            = base_url()._elm( $file_return, 'uploaded_path');
                    $i++;
                }

            }
        }
        $response['status']                        = 200;
        $response['data']                          = $fileParam;

        return $this->respond( $response );
    }

    public function upload_64( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();
        $fileParam                                 = [];


        $aConfig                                   = [
            "path"      => 'board/editor',
            'mimes'     => 'gif|jpg|jpeg|png',
        ];
        for( $i=0 ; $i < 6 ; $i++ ){
            if( empty( _elm( $requests, 'file'.$i ) ) === false ){

                $data                              = '';
                $data                              = _elm( $requests, 'file'.$i );
                list($type, $data)                 = explode(';', $data);
                list(, $data)                      = explode(',', $data);
                $data                              = base64_decode($data);

                $file_return                       = $this->_upload_base64( $data , 'file'.$i  , $aConfig );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = '파일 처리중 오류빌셍.. 다시 시도해주세요.';
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 리턴값 세팅
                #------------------------------------------------------------------
                $fileParam[$i]['src']              = base_url()._elm( $file_return, 'uploaded_path');

            }
        }
        $response['status']                        = 200;
        $response['data']                          = $fileParam;

        return $this->respond( $response );
    }

}