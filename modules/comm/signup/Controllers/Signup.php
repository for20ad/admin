<?php
#------------------------------------------------------------------
# Signup.php
# 회원가입 컨트롤러
# 김우진
# 2024-05-13 15:25:32
# @Desc :  회원가입
#------------------------------------------------------------------
namespace Module\comm\signup\Controllers;
use Module\core\Controllers\ApiController;
use Module\comm\member\Models\MemberModel;
use Module\comm\signup\Models\SignupModel;

use Config\Services;
use DOMDocument;


use Exception;

class Signup extends ApiController
{
    private $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $requests                                           = _parseJsonFormData();
        $response                                           = $this->_initApiResponse();

        $memberModel                                        = new MemberModel();
        $validation                                         = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '아이디값 누락',
                ],
            ],
            'u_pwd' => [
                'label'  => '비밀번호',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '비밀번호값 누락',
                ],
            ],
            'u_name' => [
                'label'  => '이름',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '이름값 누락',
                ],
            ],
            'u_mobile' => [
                'label'  => '휴대폰',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '휴대폰번호 누락.',
                ],
            ],
            'u_sms_flag' => [
                'label'  => 'sms 수신여부',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'sms수신여부 누락.',
                ],
            ],
            'u_mailing_flag' => [
                'label'  => '메일링 수신여부',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메일링 수신여부 누락.',
                ],
            ],
            'u_priv_appr_flag' => [
                'label'  => '개인정보 수집 및 이용 필수',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '개인정보 수집 및 이용 필수값 누락.',
                ],
            ],
            'u_priv_offer' => [
                'label'  => '개인정보 제 3자 제공 동의',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '개인정보 제 3자 제공 동의값 누락.',
                ],
            ],
            'u_priv_cons' => [
                'label'  => '개인정보 취급업무 위탁',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '개인정보 취급업무 위탁값 누락.',
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
        # TODO: 아이디 있는지 중복여부
        #------------------------------------------------------------------
        $chkParam                                           = [];
        $chkParam['u_id']                                   = _elm( $requests, 'u_id' );
        $chkParam['u_pwd']                                  = 'snsSignin';
        $chkParam['pass_login']                             = true;
        $chkMember                                          = $memberModel->findUserById( $chkParam );

        #------------------------------------------------------------------
        # TODO: 회원검색 데이터가 없어야 실행하도록 함.
        #------------------------------------------------------------------
        if( empty( $chkMember ) === true ){
            $modelParam                                     = [];
            $modelParam['MB_USERID']                        = _elm( $requests, 'u_id' );
            $modelParam['MB_PASSWORD']                      = $this->_aesEncrypt( _elm( $requests, 'u_pwd' ) );
            $modelParam['MB_NM']                            = _elm( $requests, 'u_name' );
            $modelParam['MB_NICK_NM']                       = _elm( $requests, 'u_name' );
            $modelParam['MB_MOBILE_NUM']                    = $this->_aesEncrypt( _elm( $requests, 'u_mobile' ) );
            $modelParam['MB_STATUS']                        = 2;
            $modelParam['MB_SMS_FLAG']                      = _elm( $requests, 'u_sms_flag' );
            $modelParam['MB_MAILING_FLAG']                  = _elm( $requests, 'u_mailing_flag' );
            $modelParam['MB_PRIV_APPR']                     = _elm( $requests, 'u_priv_appr_flag' );
            $modelParam['MB_PRIV_APPR_OPT']                 = _elm( $requests, 'u_priv_appr_opt' );
            $modelParam['MB_PRIV_OFFER']                    = _elm( $requests, 'u_priv_offer' );
            $modelParam['MB_PRIV_CONS']                     = _elm( $requests, 'u_priv_cons' );

            #------------------------------------------------------------------
            # TODO: 신규가입시 비밀번호가 인코딩 된 비밀번호이기 때문에 패스워드 변경 flag를 넣어준다.
            #------------------------------------------------------------------
            $modelParam['PASSWD_CHK']                       = 1;

            $modelParam['MB_REG_AT']                        = date('Y-m-d H:i:s');
            $modelParam['MB_REG_IP']                        = $_SERVER['REMOTE_ADDR'];


            $this->db->transBegin();

            $mIdx                                           = $memberModel->memberRegister( $modelParam );

            if ($this->db->transStatus() === false || $mIdx === false ) {
                $this->db->transRollback();
                $response['status']                         = 400;
                $response['messages']                       = '처리중 오류발생.. 다시 시도해주세요.';
                return  $this->respond($response, 400);
            }

            #------------------------------------------------------------------
            # TODO: r_idx 값 잇으면 rlogin 데이터 업데이트
            #------------------------------------------------------------------
            if( empty( _elm( $requests, 'r_idx' ) ) === false ){
                $snsParam                                   = [];
                $snsParam['R_IDX']                          = _elm( $requests, 'r_idx' );
                $snsParam['M_IDX']                          = $mIdx;

                $snsResult                                  = $memberModel->rloginInfoMemberJoin( $snsParam );

                if ($this->db->transStatus() === false || $snsResult === false ) {
                    $this->db->transRollback();
                    $response['status']                     = 400;
                    $response['messages']                   = 'sns데이터 업데이트중 오류발생.. 관리자에 문의하세요.';
                    return  $this->respond($response, 400);
                }

            }
            #------------------------------------------------------------------
            # TODO: 회원가입 이벤트 쿠폰 있으면 발급 (추후 개발)
            #------------------------------------------------------------------

            #------------------------------------------------------------------
            # TODO: 회원가입 쿠폰 알림톡 발송
            #------------------------------------------------------------------

            #------------------------------------------------------------------
            # TODO:  회원가입 알림톡 발송
            #------------------------------------------------------------------

            #------------------------------------------------------------------
            # TODO: 모든 처리가 끝나면 데이터 다시 리턴
            #------------------------------------------------------------------

            $this->db->transCommit();

            $memberParam                                    = [];
            $memberParam['M_IDX']                           = $mIdx;

            $memberInfo                                     = $memberModel->getMemberDefaultInfo( $memberParam );


            $response['status']                             = 200;
            $response['messages']                           = 'success';
            $response['data']                               = $memberInfo;

        }
        #------------------------------------------------------------------
        # TODO: 검색되는 회원이 있으면 에러 리턴
        #------------------------------------------------------------------
        else{
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = '동일한 아이디가 존재합니다.';
        }


        #------------------------------------------------------------------
        # TODO: 결과 리턴
        #------------------------------------------------------------------
        return $this->respond($response);

    }
    #------------------------------------------------------------------
    # FIXME: 아이디 검색. 중복확인 및 기타 아이디 검색에 사용한다.
    #------------------------------------------------------------------
    public function dupUserId()
    {

        $requests                  = _trim( $this->request->getGet() );
        $response                  = $this->_initApiResponse();
        $memberModel               = new MemberModel();
        #------------------------------------------------------------------
        # TODO: 아이디 항목이 없을때 리턴.
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_id' ) ) === true ){
            $response['status']    = 400;
            $response['error']     = 400;
            $response['messages']  = '아이디를 입력해주세요.';
            return $this->respond($response); // 4xx
            exit();
        }

        #------------------------------------------------------------------
        # TODO: 아이디 검색 후 리턴
        #------------------------------------------------------------------
        $modelParam                = [];
        $modelParam['u_id']        = _elm( $requests, 'u_id' );
        $modelParam['u_pwd']       = 'findId';
        $modelParam['pass_login']  = true;

        $userInfo                  = $memberModel->findUserById($modelParam);

        $response['status']        = 200;
        $response['messages']      = 'success';
        $response['cnt']           = is_array( _elm($userInfo, 0 ) ) === true ? count( $userInfo ) : ( empty( $userInfo ) === true ? 0 : 1 ) ;

        #------------------------------------------------------------------
        # TODO: 데이터 리턴 옵션 : true
        #------------------------------------------------------------------

        $_isRaw                    = _stringToBool( _elm( $requests, 'raw' ) );

        if( $_isRaw === true ){
            $response['data']      = $userInfo;
        }

        return $this->respond($response); // 2xx

    }

}
