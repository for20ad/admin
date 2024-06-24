<?php
#------------------------------------------------------------------
# Signin.php
# 관리자 로그인
# 김우진
# 2024-05-13 09:03:53
# @Desc : 로그인 api
#------------------------------------------------------------------

namespace Module\admin\login\Controllers;
use Module\core\Controllers\ApiController;
use Module\admin\login\Models\LoginModel;
use Module\admin\member\Models\MemberModel;

use Config\Services;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use Module\admin\login\Controllers\Token;
use App\Libraries\LicenseLib;
use Config\Encryption;


use Exception;

class Signin extends ApiController
{
    private $db;
    use ResponseTrait;
    public function __construct()
    {

        parent::__construct();
        $this->db = \Config\Database::connect();



    }

    #------------------------------------------------------------------
    # FIXME: 로그인
    #------------------------------------------------------------------

    public function index( $param = [] )
    {

        //$requests = _trim( $this->request->getPost() );
        #------------------------------------------------------------------
        # TODO: post데이터 fom-data -> json 으로 변경함. jsonparse로 변경
        #------------------------------------------------------------------
        $requests = _parseJsonFormData();


        $response = $this->_initApiResponse();



        if( empty( _elm( $requests, 'u_id' ) ) === true || empty( _elm( $requests, 'u_pwd' ) ) === true ){
            $response['status']                 = 400;
            $response['messages']               = ['data' => '로그인 정보가 없습니다. 다시 시도해주세요.'];
            return $this->respond($response); // 201
            exit();
        }

        $token = new Token();

        $response                               = $token->generateToken( $requests );

        if(  empty( _elm($requests, 'param_return') ) === false){
            return $response;
        }else{
            return $this->respond($response); // 201
        }
    }

    #------------------------------------------------------------------
    # FIXME: 토큰 검수만 진행
    #------------------------------------------------------------------
    public function tokenValidation()
    {
        $response = $this->_initApiResponse();
        $response['status']                     = 200;
        $response['data']                       = $GLOBALS['userInfo'];

        return $this->respond($response);
    }

    #------------------------------------------------------------------
    # FIXME: 토큰 강제발급
    #------------------------------------------------------------------
    public function tokenForceIssue()
    {
        #------------------------------------------------------------------
        # TODO: post데이터 fom-data -> json 으로 변경함. jsonparse로 변경
        #------------------------------------------------------------------
        $requests = _parseJsonFormData();

        if( empty( _elm( $requests, 'u_id' ) ) === true ){
            $response['status'] = 400;
            $response['messages']                = '테스트 발급할 아이디 누락.';
            return $this->respond($response); // 201
            exit();
        }

        $token = new Token();
        $response                               = $token->generateForceToken( $requests );

        if(  empty( _elm($requests, 'param_return') ) === false){
            return $response;
        }else{
            return $this->respond($response); // 201
        }
    }

    #------------------------------------------------------------------
    # FIXME: sns로그인
    #------------------------------------------------------------------
    public function snsRlogin()
    {
        #------------------------------------------------------------------
        # TODO: 변수확인 서비스 load
        #------------------------------------------------------------------
        $validation                                         = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 기본 리턴값 세팅
        #------------------------------------------------------------------
        $response                                           = $this->_initApiResponse();

        #------------------------------------------------------------------
        # TODO: post데이터 fom-data -> json 으로 변경함. jsonparse로 변경
        #------------------------------------------------------------------
        $requests                                           = _parseJsonFormData();

        #------------------------------------------------------------------
        # TODO: 모델 load
        #------------------------------------------------------------------
        $memberModel                                        = new MemberModel();

        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'r_site' => [
                'label'  => 'SNS구분',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'SNS구분값 누락',
                ],
            ],
            'r_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '아이디값 누락',
                ],
            ],
            'r_email' => [
                'label'  => '이메일',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '이메일 누락.',
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
        # TODO: sns 테이블 검색 파라메터 세팅
        #------------------------------------------------------------------
        $sParam                                             = [];
        $sParam['R_GUBUN']                                  = _elm( $requests, 'r_site' );
        $sParam['R_LOGIN_ID']                               = _elm( $requests, 'r_id' );
        $sParam['R_EMAIL']                                  = $this->_aesEncrypt(_elm( $requests, 'r_email' ) );
        $sParam['R_NAME']                                   = _elm( $requests, 'r_name' );
        $sParam['R_NICKNAME']                               = _elm( $requests, 'r_nickname' );
        $sParam['R_MOBILE']                                 = $this->_aesEncrypt( _elm( $requests, 'r_mobile' ) );


        $mData                                              = $memberModel->getRloginInfo( $sParam );

        #------------------------------------------------------------------
        # TODO: SNS 데이터가 있으면..
        #------------------------------------------------------------------
        if( empty( _elm( $mData, 'R_IDX' ) ) === false ){
            #------------------------------------------------------------------
            # TODO: SNS로그인 접속 기록은 있지만
            #      M_IDX 없으면 신규 가입으로 이동
            #------------------------------------------------------------------
            if( empty( _elm( $mData, 'M_IDX' ) ) === true  ){
                $response['status']                         = 200;
                $response['messages']                       = 'success';
                $response['data']                           = [ 'loc' => 'pre' , 'r_idx' => _elm( $mData, 'R_IDX' ) ];
                return $this->respond($response);
            }else{
                #------------------------------------------------------------------
                # TODO: sns접속기록이 있고 회원과 연결이 된 상태이면
                #      로그인정보 불러와 로그인 프로세스 진행
                #------------------------------------------------------------------
                $findParam                                  = [];
                $findParam['u_idx']                         = _elm( $mData, 'M_IDX' );


                $userInfo                                   = $memberModel->getUserInfoFromSns( $findParam );
                #------------------------------------------------------------------
                # TODO: 회원정보 검색했는데 없으면 리턴
                #------------------------------------------------------------------
                if( empty( $userInfo ) === true ){
                    $response['status']                     = 400;
                    $response['messages']                   = '일치하는 회원정보가 없습니다.';
                    return $this->respond($response);

                }

                #------------------------------------------------------------------
                # TODO: 로그인 진행을 위해 데이터 세팅
                #------------------------------------------------------------------
                $loginParam                                 = [];
                $loginParam['u_id']                         = _elm( $userInfo, 'MB_USERID' );
                $loginParam['u_pwd']                        = 'snsSignin';
                $loginParam['pass_login']                   = true;

                try{
                    $loginParam['param_return']             = true;
                    $response                               =  $this->index($loginParam);
                    return $this->respond( $response );

                }catch( Exception $e ){
                    $response['status']                     = 400;
                    $response['error']                      = 400;
                    $response['messages']                   = $e->getMessage();

                    return $this->respond($response, 400);
                }
            }

        }else{
            #------------------------------------------------------------------
            # TODO: DB Transaction 시작 수동 커밋 사용
            # 자동 사용시 --------------------------------------------------------
            # $this->db->transStart();
            # $this->db->transComplete();
            # -----------------------------------------------------------------
            #------------------------------------------------------------------
            $this->db->transBegin();

            #------------------------------------------------------------------
            # TODO: sns 로그인 정보가 없으면 insert 시작
            #------------------------------------------------------------------
            $_R_LOGIN_IDX = $memberModel->rLoginRegister( $sParam );

            if ($this->db->transStatus() === false || empty( $_R_LOGIN_IDX ) === true)
            {
                $this->db->transRollback();

                $response['status']                         = 400;
                $response['error']                          = 400;
                $response['messages']                       = '처리중 에러가 발생했습니다.<br />잠시 후 다시 시도해 주세요.';
                return $this->respond($response, 400);

            }
            #------------------------------------------------------------------
            # TODO: DB commit
            #------------------------------------------------------------------
            $this->db->transCommit();

            #------------------------------------------------------------------
            # TODO: 저장 성공 시 새로 가입 프로세스 리턴
            #------------------------------------------------------------------
            $response['status']                             = 200;
            $response['messages']                           = 'success';
            $response['data']                               = [ 'loc' => 'new', 'r_idx' => $_R_LOGIN_IDX ];

            return $this->respond($response);

        }


    }

    public function refreshAuthToken()
    {
        helper(['archie','jwt']);
        $response                               = $this->_initApiResponse();
        #------------------------------------------------------------------
        # TODO: post데이터 fom-data -> json 으로 변경함. jsonparse로 변경
        #------------------------------------------------------------------
        $requests                               = _parseJsonFormData();

        #------------------------------------------------------------------
        # TODO: refreshToken 필수값 체크
        #------------------------------------------------------------------
        if(empty(_elm($requests, 'refreshToken')) === true) {
            $response['status']                                 = 400;
            $response['error']                                  = 400;
            $response['messages']['error']                      = ['refreshToken' => 'refrshToken 정보 없음'];

            return $this->respond($response, 400);
        }

        #------------------------------------------------------------------
        # TODO:  refreshToken 유효검사
        #------------------------------------------------------------------
        if( jwtAccessTokenExpired( _elm($requests, 'refreshToken') ) === false){

            #------------------------------------------------------------------
            # TODO: 토큰 정보 분석
            #------------------------------------------------------------------
            $aReturn = validateJWTFromRequest(_elm($requests, 'refreshToken'));

            if(empty(_elm($aReturn, 'error')) === false) {
                $response['status']                               = 400;
                $response['error']                                = 400;
                if(_elm($aReturn , 'error') === 'Expired token') {
                    $response['messages']                         = '토큰이 만료되었습니다. 로그인 페이지로 이동합니다';
                }else{
                    $response['messages']                         = _elm( $aReturn, 'error' );
                }
                return $this->respond($response, 400);
            }

            #------------------------------------------------------------------
            # TODO: 토큰 재발급
            #------------------------------------------------------------------
            $token                              = new Token();
            $response                           = $token->generateToken( $aReturn );

            return $this->respond($response); // 2xx

        }else{
            return $this->respond($response);
        }
    }

}
