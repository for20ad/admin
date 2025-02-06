<?php
namespace Module\login\Controllers\apis;

use Module\core\Controllers\ApiController;
use Module\login\Models\LoginModel;
use Config\View;
use App\Libraries\KakaoLib;
use App\Libraries\OwensView;
use Config\Talk;
use Config\Site;

use App\Libraries\MemberLib;
use Config\Site as SiteConfig;

use Shared\Config as SharedConfig;

class LoginApi extends ApiController
{
    protected $sharedConfig;
    public function __construct()
    {
        parent::__construct();
        $this->sharedConfig                        = new SharedConfig();
    }

    public function loginAuth()
    {

        $response                                   = $this->_initApiResponse();
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
            'i_member_id' => [
                'label'  => '아이디',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '이이디를 입력해주세요.',
                ],
            ],
            'i_member_password' => [
                'label'  => '비밀번호',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '비밀번호를 입력해주세요.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['errors']                     = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $loginModel                                 = new LoginModel();

        $modelParam                                 = [];
        $modelParam['MB_USERID']                    = _elm($requests, 'i_member_id');
        $_tmpMemberInfo                             = $loginModel->getUserData( $modelParam );

        $aStatus                                    = 0;
        $aLoginErrCnt                               = 0;

        #------------------------------------------------------------------
        # TODO: 관리자 정보 확인
        #------------------------------------------------------------------
        if( empty( $_tmpMemberInfo ) === false ){

            #------------------------------------------------------------------
            # TODO: 로그인 상태가 잠김상태이면
            #------------------------------------------------------------------
            if( _elm( $_tmpMemberInfo, 'MB_LOGIN_STATUS' ) == 1 ){
                $response['status']                 = 501;
                $response['alert']                  = '잠금상태의 아이디 입니다. 관리자에 문의하세요.';

                return $this->respond( $response );
                exit;
            }
            #------------------------------------------------------------------
            # TODO: 비밀번호 틀린 횟수가 4회 이상이면 잠김상태로 전환
            #------------------------------------------------------------------
            else if(  _elm( $_tmpMemberInfo, 'MB_LOGIN_STATUS' ) != 1 && _elm( $_tmpMemberInfo, 'MB_PASS_ERR_COUNT' ) > 4 ){
                $aStatus = 1;
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 정보가 없으면
        #------------------------------------------------------------------
        else{
            $response['status']                     = 501;
            $response['alert']                      = '아이디 또는 비밀번호를 확인해주세요.';

            return $this->respond($response);
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 비밀번호와 함께 재검색
        #------------------------------------------------------------------
        $modelParam['MB_PASSWORD']                  = $this->_aesEncrypt( _elm( $response, 'i_member_password' ) );
        $memberInfo                                 = $loginModel->getUserData( $modelParam );


        if( empty( $memberInfo ) === true ){
            $modelParam['MB_LOGIN_STATUS']          = $aStatus;
            $aLoginErrCnt                           = _elm( $_tmpMemberInfo, 'MB_PASS_ERR_COUNT' ) + 1;
            $modelParam['MB_PASS_ERR_COUNT']        = $aLoginErrCnt== 0 ? 1 : $aLoginErrCnt;
            $loginModel->updateLoginCnt( $modelParam );
            $response['status']                     = 501;
            $response['alert']                      = '아이디 또는 비밀번호를 확인해주세요.';
            return $this->respond($response);
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 인증번호 페이지 로딩하기 위한 세팅
        #------------------------------------------------------------------
        $config                                     = new View();
        $owensView                                  = new OwensView($config);

        #------------------------------------------------------------------
        # TODO: pushsms 데이터 세팅
        #------------------------------------------------------------------
        $talkParam                                  = [];
        $talkParam['mobile_num']                    = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
        $talkParam['temp_name']                     = '인증번호3분';

        $smsResponse                                = $this->pushSms( $talkParam );
        if( _elm( $smsResponse, 'status' ) !== 200 ){
            $response['status']                     = 501;
            $response['alert']                      = '메시지 발송실패.. 다시 시도해주세요.';
            return $this->respond($response);

        }

        #------------------------------------------------------------------
        # TODO: 서브페이지 데이터 세팅
        #------------------------------------------------------------------
        $pageParam                                  = [];
        $pageDatas                                  = [];
        $pageDatas['mobile_num']                    = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
        $pageDatas['auth_num']                      = _elm( $smsResponse, 'auth_num' );
        $pageDatas['mb_idx']                        = _elm( $memberInfo, 'MB_IDX' );

        $pageParam['pageDatas']                     = $pageDatas;

        $response                                   = $this->_initApiResponse();
        $response['status']                         = 200;
        $response['otp_page']                       = $owensView->loadView('Module\login\Views\_otp_auth' , $pageParam );
        $response['alert']                          = '등록하신 휴대폰번호로 인증번호가 발송되었습니다.';

        return $this->respond($response, 200);
    }

    public function reSendAuthNum( $param = [] )
    {
        $response                                   = $this->_initApiResponse();
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
            'mb_idx' => [
                'label'  => '시퀀스',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '시퀀스값 누락. 다시 시도해주세요.',
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
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $loginModel                                 = new LoginModel();

        $modelParam                                 = [];
        $modelParam['MB_IDX']                       = _elm($requests, 'mb_idx');
        $memberInfo                                 = $loginModel->getUserData( $modelParam );
        if( empty( $memberInfo ) === true ){
            $response['status']                     = 501;
            $response['alert']                      = '잘못된 정보입니다. 다시 시도새주세요.';
            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: pushsms 데이터 세팅
        #------------------------------------------------------------------
        $talkParam                                  = [];
        $talkParam['mobile_num']                    = $this->_aesDecrypt( _elm( $memberInfo, 'MB_MOBILE_NUM' ) );
        $talkParam['temp_name']                     = '인증번호3분';

        $smsResponse                                = $this->pushSms( $talkParam );
        if( _elm( $smsResponse, 'status' ) !== 200 ){
            $response['status']                     = 501;
            $response['alert']                      = _elm( $smsResponse, 'error' );
            return $this->respond($response);

        }

        #------------------------------------------------------------------
        # TODO: 서브페이지 데이터 세팅
        #------------------------------------------------------------------

        $response                                   = $this->_initApiResponse();
        $response['status']                         = 200;
        $response['alert']                          = '등록하신 휴대폰번호로 인증번호가 발송되었습니다.';
        $response['auth_num']                       = _elm( $smsResponse, 'auth_num' );

        return $this->respond($response, 200);


    }

    public function pushSms( $param )
    {
        #------------------------------------------------------------------
        # TODO: 알림톡 데이터 세팅
        #------------------------------------------------------------------
        $config = $this->sharedConfig::$talk;

        $talk   = _elm( $config , 'item' );

        $KakaoInfo                                  = _elm( $config, 'KakaoInfo' );
        $kakaoTemplateInfo                          = _elm( $config, 'kakaoTemplate' );

        $kakao                                      = new KakaoLib;
        $talkParam                                  = [];
        $template_code                              = array_search( _elm( $param, 'temp_name' ), $kakaoTemplateInfo);

        if( empty( $template_code ) === true ){
            $returnData['status']                   = 200;
            $returnData['error']                    = '템플릿 데이터가 없습니다.';
            return $returnData;
        }
        $auth_num                                   = random_string('numeric', 4);

        $kakao_param                                = [
            'TEMPLATE_CODE'                         => $template_code,
            'YELLOWID_KEY'                          => $KakaoInfo['YELLOWID_KEY'],
            'CALLPHONE'                             => _elm($param, 'mobile_num'),
            'REQTIME'                               => '00000000000000',
            //'AGENT_FLAG'    => rand(0,4),
            'typeValues'                            => [
                'mall_name'                         => '산수유람',
                'auth_num'                          => $auth_num
            ]
        ];

        try {
            $kakao->sendATalk($kakao_param);

            $returnData                             = [];
            $returnData['status']                   = 200;
            $returnData['auth_num']                 = $auth_num;
            return $returnData;

        }
        //알림톡발송 임시 주석처리
        catch(\Exception $e) {
            $response['status']                     = '400';
            $response['alert']                      = $e->getMessage();


            return $this->fail( $e->getMessage(), 401, '303' );
        }


    }


    public function loginProc(){
        $response                                   = $this->_initApiResponse();
        $requests                                   = $this->request->getPost();

        if( _elm( $requests, 'auth_num' ) != _elm( $requests, 'i_auth_number' ) ){
            $response['status']                     = 502;
            $response['alert']                      = '인증번호가 틀립니다. 다시 시도해주세요.';
            return $this->respond($response);
        }

        $modelParam = [];
        $modelParam['MB_IDX']                       = _elm( $requests, 'mb_idx' );
        $loginModel                                 = new LoginModel();

        $memberInfo                                 = $loginModel->getUserData( $modelParam );

        #------------------------------------------------------------------
        # TODO: 로그인 세션처리
        #------------------------------------------------------------------
        $datas                                      = [
            '_memberInfo'                           => [
                'member_idx'                        => _elm( $memberInfo, 'MB_IDX' ),
                'member_name'                       => _elm( $memberInfo, 'MB_USERNAME' ),
                'member_id'                         => _elm( $memberInfo, 'MB_USERID' ),
                'member_level'                      => _elm( $memberInfo, 'MB_LEVEL' ),
                'member_group_idx'                  => _elm( $memberInfo, 'MB_GROUP_IDX' )
            ],
        ];
        $this->session->set($datas);

        #------------------------------------------------------------------
        # TODO:  TEMP_ADMIN_LOGIN_TIME 지정
        #------------------------------------------------------------------
        $siteConfig                                 = new Site();
        $admin_expire_time                          = (int)_elm($siteConfig, 'adminExpireTime', 7200);

        $this->session->set('TEMP_ADMIN_LOGIN_TIME', time(), $admin_expire_time);


        session_write_close();


        $modelParam['MB_IDX']                       = _elm( $memberInfo, 'MB_IDX' );
        $modelParam['MB_PASS_ERR_COUNT']            = 0;
        $loginModel->updateLoginCnt( $modelParam );

        $modelParam['MB_LAST_LOGIN_AT']             = date('Y-m-d H:i:s');
        $modelParam['MB_LAST_LOGIN_IP']             = _elm( $_SERVER, 'REMOTE_ADDR' );
        $loginModel->updateLoginDate( $modelParam );




        $response['alert']                          = '로그인 되었습니다.';
        $response['status']                         = 200;
        $response['redirect_url']                   = "/main";


        return $this->respond($response, 200);

    }

     // 로그인 확인
    public function checkLogin($param = [])
    {
        $response                                   = $this->_initResponse();
        $memberLib                                  = new MemberLib();

        if ($memberLib->isAdminLogin() === true)
        {
            $response['status']                     = 'true';
        }
        else
        {
            $response['status']                     = 'false';
            $response['replace_url']                = _link_url('/login');
        }

        return $this->respond( $response );
    }

    // 로그인 연장

    public function delayLogin($param = [])
    {
        $response = $this->_initResponse();
        $memberLib = new MemberLib();
        $site_config = new SiteConfig();

        if ($memberLib->isAdminLogin() === true)
        {
            $current_time = time();
            $admin_expire_time = (int)$site_config->adminExpireTime ?? 7200; // 2시간 = 7200초

            $this->session->set('TEMP_ADMIN_LOGIN_TIME', $current_time);

            $response['status'] = 'true';
            $response['admin_auth_time'] = $admin_expire_time;
        }
        else
        {
            $response['status'] = 'false';
            $response['replace_url'] = _link_url('/login');
        }
        return $this->respond($response);
    }



}