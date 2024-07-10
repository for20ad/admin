<?php
#------------------------------------------------------------------
# MemberApi.php
# 관리자 member API
# 김우진
# 2024-07-04 10:11:57
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\MemberModel as adminModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

use Config\Services;
use DOMDocument;
class MemberApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }
    public function duplicateId( $param = [] )
    {
        $response                                   = $this->_initResponse();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }


        $requests                                   = $this->request->getPost();

        $memberModel                                = new adminModel();

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_user_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '아이디를 입력해주세요.',
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
        # TODO: 아이디 중복검사
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['MB_USERID']                    = _elm($requests, 'i_user_id', 0);

        $aData                                      = $memberModel->findAdmUserId( $modelParam );

        if( !empty( $aData ) ){
            $response['status']                     = 500;
            $response['alert']                      = '중복된 아이디입니다.';
        }else{
            $response['status']                     = 200;
            $response['alert']                      = '';
        }

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond( $response );

    }

    public function memberRegister( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $adminModel                                 = new adminModel();


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_user_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required|is_unique[ADMIN_MEMBER.MB_USERID]',
                'errors' => [
                    'required'      => '아이디를 입력하세요.',
                    'unique_user_id' => '아이디가 이미 존재합니다.',
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
            'i_admin_group' => [
                'label'  => '관리자 그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '관리자 그룹을 선택하세요.',
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

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 회원 저장 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];

        #------------------------------------------------------------------
        # FIXME: 기본정보
        #------------------------------------------------------------------
        $modelParam['MB_USERID']                    = _elm( $requests, 'i_user_id' );
        $modelParam['MB_PASSWORD']                  = $this->_aesEncrypt( _elm( $requests, 'i_password' ) );
        $modelParam['MB_USERNAME']                  = _elm( $requests, 'i_user_name' );
        $modelParam['MB_MOBILE_NUM']                = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_mobile_num' ) ) ) ;
        $modelParam['MB_LEVEL']                     = _elm( $requests, 'i_admin_group' );

        #------------------------------------------------------------------
        # FIXME: 추가정보
        #------------------------------------------------------------------
        $modelParam['MB_TEL_NUM']                   = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_tel_num' ) ) ) ;
        $modelParam['MB_EMAIL']                     = $this->_aesEncrypt( _elm( $requests, 'i_email' ) );
        $modelParam['MB_DEPARTMENT']                = _elm( $requests, 'i_department' );
        $modelParam['MB_POSITION']                  = _elm( $requests, 'i_position' );
        $modelParam['MB_STATUS']                    = _elm( $requests, 'i_status' );
        $modelParam['MB_CREATE_AT']                 = date( 'Y-m-d H:i:s' );
        $modelParam['MB_CREATE_IP']                 = _elm( $_SERVER, 'REMOTE_ADDR' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $mIdx                                       = $adminModel->insertAdminMember( $modelParam );
        if ( $this->db->transStatus() === false || $mIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 회원그룹에 추가
        #------------------------------------------------------------------
        $groupParam                                 = [];
        $groupParam['MB_GROUP_IDX']                 = _elm( $modelParam, 'MB_LEVEL' );
        $groupParam['MB_IDX']                       = $mIdx;
        $groupParam['MB_GM_AT']                     = date( 'Y-m-d H:i:s' );

        $gIdx                                       = $adminModel->insertGroupMember( $groupParam );
        if ( $this->db->transStatus() === false || $gIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '그룹 저장중 에러발생. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '관리자 회원 추가 - Data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
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
        $response['redirect_url']                   = _link_url( '/setting/memberLists' );

        return $this->respond( $response );

    }

    public function getAdminLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $adminModel                                 = new adminModel();
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
        $modelParam['MB_LEVEL']                     = _elm( $requests, 's_group' );



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
                case 'mb_mobile' :
                    $modelParam['MB_MOBILE_NUM']    = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests , 's_keyword' ) ) );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' MB_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' MB_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' MB_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' MB_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' MB_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;



        $aLISTS_RESULT                              = $adminModel->getAdminMemberLists($modelParam);
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

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\setting\Views\member\admin_lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/setting/memberLists' ), '/');
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


    public function memberModify()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $adminModel                                 = new adminModel();


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
                    'unique_user_id' => '아이디가 이미 존재합니다.',
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
            'i_admin_group' => [
                'label'  => '관리자 그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '관리자 그룹을 선택하세요.',
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

        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 회원 저장 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        #------------------------------------------------------------------
        # FIXME: 기본정보
        #------------------------------------------------------------------
        $modelParam['MB_IDX']                       = _elm( $requests, 'i_mb_idx' );
        $aData                                      = $adminModel->getAdminMemberData( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 500;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        $modelParam['MB_USERID']                    = _elm( $requests, 'i_user_id' );
        if( empty( _elm( $requests, 'i_password' ) ) === false && empty( _elm( $requests, 'i_password_check' ) ) === false ){
            $modelParam['MB_PASSWORD']              = $this->_aesEncrypt( _elm( $requests, 'i_password' ) );
        }
        $modelParam['MB_USERNAME']                  = _elm( $requests, 'i_user_name' );
        $modelParam['MB_MOBILE_NUM']                = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_mobile_num' ) ) ) ;
        $modelParam['MB_LEVEL']                     = _elm( $requests, 'i_admin_group' );

        #------------------------------------------------------------------
        # FIXME: 추가정보
        #------------------------------------------------------------------
        $modelParam['MB_TEL_NUM']                   = $this->_aesEncrypt( preg_replace('/[^0-9]/', '', _elm( $requests, 'i_tel_num' ) ) ) ;
        $modelParam['MB_EMAIL']                     = $this->_aesEncrypt( _elm( $requests, 'i_email' ) );
        $modelParam['MB_DEPARTMENT']                = _elm( $requests, 'i_department' );
        $modelParam['MB_POSITION']                  = _elm( $requests, 'i_position' );
        $modelParam['MB_STATUS']                    = _elm( $requests, 'i_status' );
        $modelParam['MB_UPDATE_AT']                 = date( 'Y-m-d H:i:s' );
        $modelParam['MB_UPDATE_IP']                 = _elm( $_SERVER, 'REMOTE_ADDR' );

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $mIdx                                       = $adminModel->updateAdminMember( $modelParam );
        if ( $this->db->transStatus() === false || $mIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 회원그룹에 수정
        #------------------------------------------------------------------
        if( _elm( $aData, 'MB_LEVEL' ) != _elm( $requests, 'i_admin_group' ) ){


            $groupParam                             = [];
            $groupParam['MB_GROUP_IDX']             = _elm( $modelParam, 'MB_LEVEL' );
            $groupParam['MB_IDX']                   = $mIdx;


            $gIdx                                   = $adminModel->updateGroupMember( $groupParam );
            if ( $this->db->transStatus() === false || $gIdx === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '그룹 저장중 에러발생. 다시 시도해주세요.';
                return $this->respond( $response );
            }

        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '관리자 회원 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ).' / newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
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
        $response['redirect_url']                   = _link_url( '/setting/memberDetail/'._elm( $modelParam, 'MB_IDX' ) );

        return $this->respond( $response );
    }

    public function memberDelete()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $adminModel                                 = new adminModel();

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['MB_IDX']                       = _elm( $requests, 'i_mb_idx' );
        $modelParam['MB_STATUS']                    = '9';

        $aData                                      = $adminModel->getAdminMemberData( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 500;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 상태값 업데이트 (삭제처리)
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $adminModel->updateAdminMemberStatus( $modelParam );
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
        $logParam['MB_HISTORY_CONTENT']             = '관리자 회원 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제가 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/memberLists' );

        return $this->respond( $response );

    }
}