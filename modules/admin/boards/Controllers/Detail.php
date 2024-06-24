<?php
#------------------------------------------------------------------
# Detail.php
# 상세페이지 관련
# 김우진
# 2024-05-27 16:34:50
# @Desc :
#------------------------------------------------------------------

namespace Module\admin\boards\Controllers;
use Module\admin\boards\Controllers\Boards;


use Config\Services;
use DOMDocument;

class Detail extends Boards
{

    public function __construct()
    {
        parent::__construct();
    }

    public function postsData( $param = [] )
    {

        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();


        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $setRules                                   = [
            'u_board_id' => [
                'label'  => '게시판 ID',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 ID 누락',
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

        foreach( $aData  as $key => $data  ){
            if( $key == 'P_CONTENT' ){
                $aData[ $key ] = htmlspecialchars_decode( $data );
            }
        }
        #------------------------------------------------------------------
        # TODO: 비밀글일때 글쓴이와 본인이 아니면 글 열람 금지
        #------------------------------------------------------------------
        // if( _elm( $aData, 'P_SECRET' ) == 'Y' && _elm( $aData, 'P_WRITER_IDX' ) != _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' ) ){
        //     $response['status']                        = 400;
        //     $response['error']                         = 400;
        //     $response['messages']                      = '비밀글은 본인만 확인 가능합니다.';

        //     return $this->respond( $response );
        // }

        // if( _elm( $aData, 'P_SECRET' ) == 'Y' ){
        //     #------------------------------------------------------------------
        //     # TODO: 비밀번호 자릿수 체크
        //     #------------------------------------------------------------------
        //     if( strlen( _elm( $requests, 'u_password' ) ) != _elm( $boardConfig, 'B_PASSWORD' ) ){
        //         $response['status']                    = 400;
        //         $response['error']                     = 400;
        //         $response['messages']                  = '비밀번호를 정확히 입력해주세요.';

        //         return $this->respond( $response );
        //     }
        //     #------------------------------------------------------------------
        //     # TODO:  비밀번호 체크
        //     #------------------------------------------------------------------

        //     if( $this->_aesEncrypt( _elm( $requests, 'u_password' ) )  != _elm( $aData, 'P_PASSWORD' ) ){
        //         $response['status']                    = 400;
        //         $response['error']                     = 400;
        //         $response['messages']                  = '비밀번호를 정확히 입력해주세요.';

        //         return $this->respond( $response );
        //     }
        // }
        #------------------------------------------------------------------
        # TODO: 파일 정보 load
        #------------------------------------------------------------------
        $aData['FILEINFO']                             = $this->boardsModel->getPostsInFiles( $modelParam );


        #------------------------------------------------------------------
        # TODO: 댓글 달수있는 게시판일 경우 댓글도 불러온다
        #------------------------------------------------------------------
        if( _elm( $boardConfig, 'B_COMMENT' ) == 'Y' ){
            $comments                                  = $this->boardsModel->getPostsComments( $modelParam );
            $aData['COMMENTS']                         = _build_tree( $comments, _elm($modelParam, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX' );
        }

        #------------------------------------------------------------------
        # TODO: 조회수 사용 여부에 따라 본인이 아닌 다른 사람이면 조회수 증가( 관리자는 히트나 기타 로그는 필요없어 주석처리함. )
        #------------------------------------------------------------------

        // if( _elm( $boardConfig, 'B_HITS' ) == 'Y' ){

        //     if( (int) _elm( $aData, 'P_WRITER_IDX' ) !==  (int) _elm( _elm( $GLOBALS, 'userInfo' ) , 'MB_IDX' ) ){
        //         #------------------------------------------------------------------
        //         # TODO: 조회수 어뷰징 막기위해
        //         #------------------------------------------------------------------

        //         $chkParam                               = [];
        //         $chkParam['L_MB_IDX']                     = _elm( _elm( $GLOBALS, 'userInfo' ) , 'MB_IDX' );
        //         $chkParam['L_GBN']                        = "view";
        //         $chkParam['L_POSTS_IDX']                  = _elm( $requests, 'u_idx' );

        //         $chkTimeNotOverData                       = $this->boardsModel->getPostsLogs( $chkParam );
        //         $addPossible                              = true;


        //         if( empty( $chkTimeNotOverData ) === false ){
        //             #------------------------------------------------------------------
        //             # TODO: 시간체크 10초 이하일때 조회수 증가 하지 않도록 한다.
        //             #------------------------------------------------------------------

        //             $sec                                  = _time_diff( _elm( $chkTimeNotOverData, 'L_CREATE_AT' ), date('Y-m-d H:i:s'), '%S' );

        //             if( $sec <= 10 ){
        //                 $addPossible                      = false;
        //             }

        //         }


        //         if( $addPossible == true ){
        //             #------------------------------------------------------------------
        //             # TODO: 빈값이면 저장
        //             #------------------------------------------------------------------
        //             $chkParam['L_CREATE_AT']              = date('Y-m-d H:i:s');
        //             $chkParam['L_CREATE_IP']              = _elm( $_SERVER, 'REMOTE_ADDR' );

        //             #------------------------------------------------------------------
        //             # TODO: 오류나도 무시
        //             #------------------------------------------------------------------
        //             @$this->registerModel->setPostsLog( $chkParam );

        //             #------------------------------------------------------------------
        //             # TODO: 조회수 증가 시키기
        //             #------------------------------------------------------------------
        //             @$this->registerModel->addPostsHit($modelParam['P_IDX'] );
        //         }



        //     }
        // }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시물 조회 - P_IDX : '._elm( $requests, 'u_idx' );

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $response['status']                        = 200;
        $response['messages']                      = 'success';
        $response['data']                          = $aData;

        return $this->respond( $response );
    }


}