<?php
#------------------------------------------------------------------
# Lists.php
# 게시판 리스트관련 컨트롤러
# 김우진
# 2024-05-22 16:01:45
# @Desc :
#------------------------------------------------------------------

namespace Module\admin\boards\Controllers;
use Module\admin\boards\Controllers\Boards;

use Config\Services;
use DOMDocument;

class Lists extends Boards
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function boardLists( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = $this->request->getGet();


        if( empty( $param ) === false  ){
            $requests                              = $param;
        }
        $modelParam                                = [];
        $perPage                                   = _elm( $requests, 'per_page' ) ?? 20;
        $page                                      = _elm($requests, 'page') ?? 1;

        $modelParam['START']                       = ($page - 1) * $perPage  ;
        $modelParam['LIMIT']                       = $perPage;

        $lists                                     = $this->boardsModel->getBoardLists( $modelParam );


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = 'BOARD 리스트 조회 -  orgData: '.json_encode( $lists, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $response['status']                        = 200;
        $response['messages']                      = 'success';
        $response['now_page']                      = _elm( $requests, 'page' );
        $response['totalCount']                    = _elm( $lists, 'totalCount' );
        $response['data']                          = _elm( $lists, 'lists' );


        return $this->respond( $response );
    }

    public function postsLists( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = $this->request->getGet();

        if( empty( $param ) === false  ){
            $requests                              = $param;
        }

        $modelParam                                = [];
        $validation                                = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_board_id' => [
                'label'  => '게시판ID',
                'rules'  => 'trim|required|alpha_custom|min_length[1]|max_length[30]',
                'errors' => [
                    'required' => '게시판 ID값 누락',
                ],
            ],
        ]);
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
        # TODO: 게시판 존재여부
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['B_ID']                         = _elm( $requests, 'u_board_id' );
        $checkBoards                                = $this->boardsModel->getBoardsInfoById( $modelParam );

        if( empty( $checkBoards ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '존재하지 않은 게시판입니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }
        unset( $modelParam['B_IDX'] );

        #------------------------------------------------------------------
        # TODO: 게시물 가져오기
        #------------------------------------------------------------------

        $modelParam['P_B_ID']                       = _elm( $checkBoards, 'B_ID' );

        #------------------------------------------------------------------
        # TODO: 검색조건 - 카테고리
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_category' ) ) === false ){
            $modelParam['P_C_CODE']                 = _elm( $requests, 'u_category' );
        }
        #------------------------------------------------------------------
        # TODO: 검색조건 - 키워드
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_condition' ) ) === false && empty( _elm( $requests, 'u_keyword' ) ) === false ){
            $modelParam[ _elm( $requests, 'u_condition' ) ] = _elm( $requests, 'u_keyword' );
        }

        #------------------------------------------------------------------
        # TODO: 검색조건 - 검색일
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_start_date' ) ) === false && empty( _elm( $requests, 'u_end_date' ) ) === false ){
            $modelParam['P_START_DATE']             = date( 'Y-m-d', strtotime( _elm( $requests, 'u_start_date' ) ) );
            $modelParam['P_END_DATE']               = date( 'Y-m-d', strtotime( _elm( $requests, 'u_end_date' ) ) );
        }

        $perPage                                    = _elm( $requests, 'per_page' ) ?? 20;
        $page                                       = _elm($requests, 'page') ?? 1;

        $modelParam['START']                        = ($page - 1) * $perPage  ;
        $modelParam['LIMIT']                        = $perPage;

        $lists                                     = $this->boardsModel->getPostsLists( $modelParam );


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시판 리스트 조회 -  '._elm( $checkBoards, 'B_TITLE' ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $response['status']                        = 200;
        $response['messages']                      = 'success';
        $response['now_page']                      = _elm( $requests, 'page' );
        $response['totalCount']                    = _elm( $lists, 'totalCount' );
        $response['data']                          = _elm( $lists, 'lists' );

        return $this->respond( $response );
    }


}