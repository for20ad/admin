<?php
#------------------------------------------------------------------
# Lists.php
# comm 리스트 관련 컨트롤러
# 김우진
# 2024-06-10 10:36:21
# @Desc :
#------------------------------------------------------------------

namespace Module\comm\boards\Controllers;
use Module\comm\boards\Controllers\Boards;

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
        if( empty( _elm( $lists, 'lists' ) ) === false ){
            foreach( _elm( $lists, 'lists' ) as $key => $list ){
                $lists['lists'][$key]['B_CATEGORY_NAME'] = '';
                $c_code                            = explode( '|', _elm( $list, 'B_CATEGORY_CODE' ) );
                if( empty( $c_code ) === false ){
                    $c_names                       = [];

                    foreach( $c_code as $code ){
                        $_cname                    = [];
                        $_cname                    = $this->boardsModel->getCategoryName( $code );
                        $c_names[]                 = _elm( $_cname, 'C_NAME' );
                    }
                }

                $lists['lists'][$key]['B_CATEGORY_NAME'] = join( "|", $c_names );

            }
        }


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

        #------------------------------------------------------------------
        # TODO: JWT TOKEN 수동 검증.
        #------------------------------------------------------------------
        $uReturn                                   = _getUserInfo();

        $is_member_checked                         = false;

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
        $boardConfig                                = $this->boardsModel->getBoardsInfoById( $modelParam );

        if( empty( $boardConfig ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '존재하지 않은 게시판입니다. 시퀀스값을 확인해주세요.';

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
        unset( $modelParam['B_IDX'] );

        #------------------------------------------------------------------
        # TODO: 게시물 가져오기
        #------------------------------------------------------------------

        $modelParam['P_B_ID']                       = _elm( $boardConfig, 'B_ID' );

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


        $response['status']                        = 200;
        $response['messages']                      = 'success';
        $response['now_page']                      = _elm( $requests, 'page' );
        $response['totalCount']                    = _elm( $lists, 'totalCount' );
        $response['data']                          = _elm( $lists, 'lists' );


        return $this->respond( $response );
    }
}