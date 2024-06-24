<?php
namespace Module\comm\boards\Controllers;

use Module\comm\boards\Controllers\Boards;


use Config\Services;
use DOMDocument;
use Module\comm\boards\Models\DeleteModel;

class Register extends Boards
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function postsRegister( $param = [] )
    {
        $response                                  = $this->_initApiResponse();

        $requests                                  = _parseJsonFormData();

        #------------------------------------------------------------------
        # TODO: JWT TOKEN 수동 검증.
        #------------------------------------------------------------------
        $uReturn                                   = _getUserInfo();

        $is_member_checked                         = false;

        $files                                     = $_FILES;

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

        #------------------------------------------------------------------
        # TODO: 게시판 형식에 따른 구분 ( 자유형식인지 회원전용인지 )
        #------------------------------------------------------------------

        if( _elm( $boardConfig, 'B_IS_FREE' ) == 'N' ){
            $is_member_checked                     = true;
        }

        if( $is_member_checked == true ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) == true ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '회원전용 게시판입니다. 로그인 후 이용해주세요.';

                return $this->respond( $response );
            }
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

        if( $is_member_checked === false ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) === true ){
                $setRules                             += [
                    'u_writer' => [
                        'label'  => '작성자명',
                        'rules'  => 'trim|required',
                        'errors' => [
                            'required' => '작성자명 누락',
                        ],
                    ],
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




        $modelParam['P_STATUS']                    = 1;

        $modelParam['P_LINK_URL']                  = _elm( $requests, 'u_link_url', null, true ) ?? '' ;
        $modelParam['P_TITLE']                     = _elm( $requests, 'u_title' );
        $modelParam['P_CONTENT']                   = htmlspecialchars( _elm( $requests, 'u_content' ) );
        $modelParam['P_SECRET']                    = _elm( $requests, 'u_secret' );

        #------------------------------------------------------------------
        # TODO:  비밀글 일때 비밀번호 인코딩
        #------------------------------------------------------------------
        $modelParam['P_PASSWORD']                  = _elm( $requests, 'u_secret' ) === 'Y'? $this->_aesEncrypt( _elm( $requests, 'u_password') ): null;


        $modelParam['P_WRITER_IDX']                = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );
        $modelParam['P_WRITER_NAME']               = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_NM' );

        if( empty( _elm( $requests, 'u_writer' ) ) === false ){
            $modelParam['P_WRITER_NAME']           = _elm( $requests, 'u_writer' );
        }
        #------------------------------------------------------------------
        # TODO: 글 노출 기간 있을때 설정
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_start_date' ) ) === false && empty( _elm( $requests, 'u_end_date' ) ) === false ){
            $modelParam['P_START_DATE']            = date( 'Y-m-d', strtotime( _elm( $requests, 'u_start_date' ) ) );
            $modelParam['P_END_DATE']              = date( 'Y-m-d', strtotime( _elm( $requests, 'u_end_date' ) ) );
        }

        $modelParam['P_CREATE_AT']                 = date( 'Y-m-d H:i:s' );
        $modelParam['P_CREATE_IP']                 = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['P_CREATE_MB_IDX']             = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX', null, true );


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
                    $response['messages']          = '파일 처리중 오류빌셍.. 다시 시도해주세요.';
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
        # TODO: 사용자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 등록 -  B_IDX: '.$mIdx.' => data: '.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX', null, true );

        $this->LogModel->insertUserLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 사용자 로그남기기 E
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

        $is_member_checked                         = false;


        #------------------------------------------------------------------
        # TODO: JWT TOKEN 수동 검증.
        #------------------------------------------------------------------
        $uReturn                                   = _getUserInfo();

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
                    'required' => '게시판 ID값 누락',
                ],
            ],
            'u_idx' => [
                'label'  => '게시글 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시글 IDX 누락',
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
        #------------------------------------------------------------------
        # TODO: 게시판 유무 확인
        #------------------------------------------------------------------

        $modelParam['B_ID']                        = _elm( $requests, 'u_board_id' );
        $boardConfig                               = $this->boardsModel->getBoardsInfoById( $modelParam );

        if( empty( $boardConfig ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '존재하지 않는 게시판입니다. 게시판 아이디를 확인해주세요.';

            return $this->respond( $response );
        }

        if( _elm( $boardConfig, 'B_IS_FREE' ) == 'N' ){
            $is_member_checked                     = true;
        }

        if( $is_member_checked == true ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) == true ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '회원전용 게시판입니다. 로그인 후 이용해주세요.';

                return $this->respond( $response );
            }
        }

        unset( $modelParam['B_ID'] );

        #------------------------------------------------------------------
        # TODO: 게시글 유무 확인
        #------------------------------------------------------------------
        $modelParam['P_IDX']                       = _elm( $requests, 'u_idx' );
        $aData                                     = $this->boardsModel->getPostsInfo( $modelParam );

        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '게시물이 존재하지 않습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        if( $is_member_checked === true ){
            #------------------------------------------------------------------
            # TODO: 회원 전용 게시판의 경우
            #------------------------------------------------------------------
            if( _elm( $aData, 'P_WRITER_IDX' ) != _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' ) ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '수정권한이 없습니다.';

                return $this->respond( $response );
            }
            if( _elm( $aData, 'P_SECRET' ) === 'Y' ){
                if( $this->_aesEncrypt(_elm( $requests, 'u_password' ) ) !== _elm( $aData, 'P_PASSWORD' ) ){
                    $response['status']            = 400;
                    $response['error']             = 400;
                    $response['messages']          = '비밀번호를 정확히 입력해주세요.';

                    return $this->respond( $response );
                }
            }
        }else{
            if( $this->_aesEncrypt(_elm( $requests, 'u_password' ) ) !== _elm( $aData, 'P_PASSWORD' ) ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '비밀번호를 정확히 입력해주세요.';

                return $this->respond( $response );
            }
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

        if( $is_member_checked === false ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) === true ){
                $setRules                          += [
                    'u_writer' => [
                        'label'  => '작성자명',
                        'rules'  => 'trim|required',
                        'errors' => [
                            'required' => '작성자명 누락',
                        ],
                    ],
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

        $aData['fileInfo']                         = $this->boardsModel->getPostsInFiles( $modelParam );
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

        $modelParam['P_B_ID']                      = _elm( $requests, 'u_board_id' );
        $modelParam['P_C_CODE']                    = _elm( $requests, 'u_category_code' );

        $modelParam['P_WRITER_IDX']                = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        $modelParam['P_WRITER_NAME']               = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_NM' );

        if( empty( _elm( $requests, 'u_writer' ) ) === false ){
            $modelParam['P_WRITER_NAME']           = _elm( $requests, 'u_writer' );
        }

        #------------------------------------------------------------------
        # TODO: 상태값 기본 1
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_status') ) === false ){
            $modelParam['P_STATUS']                = _elm( $requests, 'u_status');
        }
        if( empty( _elm( $requests, 'u_status') ) === false ){
            $modelParam['P_LINK_URL']              = _elm( $requests, 'u_link_url', null, true ) ?? '' ;
        }

        $modelParam['P_TITLE']                     = _elm( $requests, 'u_title' );


        $modelParam['P_CONTENT']                   = htmlspecialchars( _elm( $requests, 'u_content' ) );

        if( empty( _elm( $requests, 'u_status') ) === false ){
            $modelParam['P_SECRET']                = _elm( $requests, 'u_secret' );
            if( empty( _elm( $requests, 'u_password') ) === false ){
                #------------------------------------------------------------------
                # TODO:  비밀글 일때 비밀번호 인코딩
                #------------------------------------------------------------------
                $modelParam['P_PASSWORD']          = _elm( $requests, 'u_secret' ) === 'Y'? $this->_aesEncrypt( _elm( $requests, 'u_password') ): null;
            }

        }


        #------------------------------------------------------------------
        # TODO: 글 노출 기간 있을때 설정
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_start_date' ) ) === false && empty( _elm( $requests, 'u_end_date' ) ) === false ){
            $modelParam['P_START_DATE']            = date( 'Y-m-d', strtotime( _elm( $requests, 'u_start_date' ) ) );
            $modelParam['P_END_DATE']              = date( 'Y-m-d', strtotime( _elm( $requests, 'u_end_date' ) ) );
        }

        $modelParam['P_UP_AT']                     = date( 'Y-m-d H:i:s' );
        $modelParam['P_UP_IP']                     = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['P_UP_MB_IDX']                 = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        #------------------------------------------------------------------
        # TODO: 데이터 수정
        #------------------------------------------------------------------
        $this->db->transBegin();
        $aStatus                                   = $this->registerModel->modifyPosts( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                              = 400;
            $response['messages']                            = '데이터 수정 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
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
        # TODO: 사용자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 수정 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).' => newData: '.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX', null, true );

        $this->LogModel->insertUserLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 사용자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond( $response );


    }

    public function commentRegister( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $is_secret_veiw                            = false;

        if( empty( $param ) === false ){
            $requests                              = $param;
        }

        #------------------------------------------------------------------
        # TODO: JWT TOKEN 수동 검증.
        #------------------------------------------------------------------
        $uReturn                                   = _getUserInfo();

        $is_member_checked                         = false;

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

        if( empty( $boardConfig ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '존재하지 않는 게시판입니다. 게시판 아이디를 확인해주세요.';

            return $this->respond( $response );
        }

        if( _elm( $boardConfig, 'B_IS_FREE' ) == 'N' ){
            $is_member_checked                     = true;
        }

        if( $is_member_checked == true ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) == true ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '회원전용 게시판입니다. 로그인 후 이용해주세요.';

                return $this->respond( $response );
            }
        }

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

        if( $is_member_checked === false ){
            if( empty( _elm( $GLOBALS, 'userInfo' ) ) === true ){
                $setRules                          += [
                    'u_writer' => [
                        'label'  => '작성자명',
                        'rules'  => 'trim|required',
                        'errors' => [
                            'required' => '작성자명 누락',
                        ],
                    ],
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

        unset( $modelParam['B_ID'] );

        $modelParam['P_IDX']                       = _elm( $requests, 'u_idx' );
        $aData                                     = $this->boardsModel->getPostsInfo( $modelParam );

        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '게시물이 존재하지 않습니다. 다시 시도해주세요.';

            return $this->respond( $response );
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
        if( _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' ) ==  _elm( $aData, 'P_WRITER_IDX' )  ){
            $is_secret_veiw                        = true;
        }

        #------------------------------------------------------------------
        # TODO: 사용자 댓글중 비밀글이면 작성자와 본문 작성자만 볼수 있도록 한다.
        #------------------------------------------------------------------


        if( _elm( $boardConfig, 'B_COMMENT' ) == 'Y' ){
            $commParam                                 = [];
            if( _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' ) ==  _elm( $aData, 'P_WRITER_IDX' )  ){
                $is_secret_veiw                        = true;
            }
            $commParam['SECRET_VIEW']                  = $is_secret_veiw;
            $commParam['F_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

            $commParam['P_IDX']                        = _elm( $modelParam, 'C_B_IDX' );
            $comments                                  = $this->boardsModel->getPostsComments( $commParam );

            $aData['COMMENTS']                         = _build_tree( $comments, 0, 'C_IDX', 'C_PARENT_IDX' );
        }

        #------------------------------------------------------------------
        # TODO: 사용자 로그남기기 S
        #------------------------------------------------------------------

        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시글 댓글 입력 -  data: '.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertUserLog( $logParam );

        #------------------------------------------------------------------
        # TODO: 사용자 로그남기기 E
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

        $files                                     = $_FILES;
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

            foreach( $files as $key => $file ){
                $file_return                       = $this->_upload( $file, $config );

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
                $fileParam[$key]['src']            = base_url()._elm( $file_return, 'uploaded_path');

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
