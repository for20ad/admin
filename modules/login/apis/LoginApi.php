<?php
namespace Module\login\Controllers\apis;

use Module\core\Controllers\ApiController;
use Module\login\Models\LoginModel;
use Config\View;
use App\Libraries\KakaoLib;
use App\Libraries\OwensView;
use Config\Talk;

class LoginApi extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function loginAuth()
    {
        $post = _trim($this->request->getPost());

        $rules = [
            'i_member_id' => [
                'label' => '아이디',
                'rules' => 'required'
            ],
            'i_member_password' => [
                'label' => '비밀번호',
                'rules' =>'required'
            ]
        ];

        $messages = [
            'i_member_id' => [
                'required' => '아이디를 입력해 주세요.',
            ],
            'i_member_password' => [
                'required' => '비밀번호를 입력해 주세요.'
            ]
        ];

        if ($this->validate($rules, $messages) === false)
        {
            return $this->fail($this->validator->getErrors()); // 400
        }

        $loginModel = new LoginModel();

        $modelParam           = [];
        $modelParam['MB_UID'] = _elm($post, 'i_member_id');
        $_tmpMemberInfo = $loginModel->getUserData( $modelParam );

        $aStatus = 0;
        $aLoginErrCnt = 0;

        if( empty( $_tmpMemberInfo ) === false ){
            if( _elm( $_tmpMemberInfo, 'MB_LOGIN_STATUS' ) == 1 ){
                return $this->respond(['alert'=>'잠금상태의 아이디 입니다. 관리자에 문의하세요.' , 'status'=>203], 203);
                exit;
            }else if(  _elm( $_tmpMemberInfo, 'MB_LOGIN_STATUS' ) != 1 && _elm( $_tmpMemberInfo, 'MB_PASS_ERR_COUNT' ) > 4 ){
                $aStatus = 1;
            }
        }else{
            return $this->respond(['alert'=>'아이디 또는 비밀번호를 확인해주세요.' , 'status'=>401], 401);
            exit;
        }


        $modelParam['MB_UPW'] = _elm($post, 'i_member_password');
        $memberInfo = $loginModel->getUserData( $modelParam );


        if( empty( $memberInfo ) === true ){
            $modelParam['MB_LOGIN_STATUS'] = $aStatus;
            $aLoginErrCnt = _elm( $_tmpMemberInfo, 'MB_PASS_ERR_COUNT' ) + 1;
            $modelParam['MB_PASS_ERR_COUNT'] = $aLoginErrCnt==0?1:$aLoginErrCnt;
            $loginModel->updateLoginCnt( $modelParam );
            return $this->respond(['alert'=>'아이디 또는 비밀번호를 확인해주세요.' , 'status'=>203], 203);
            exit;
        }



        //page load 하기 위한 세팅
        $config = new View();
        $archieView = new OwensView($config);

        //pushsms 데이터 세팅
        $talkParam               = [];
        $talkParam['mobile_num'] = _elm( $memberInfo, 'MB_MOBILE_DEC' );
        $talkParam['temp_name'] = '인증번호2';

        $smsResponse             = $this->pushSms( $talkParam );
        if( _elm( $smsResponse, 'status' ) !== 200 ){
            return $this->respond(['alert'=>'메시지 발송실패.. 다시 시도해주세요.' , 'status'=>401], 401);

        }

        //서브페이지 데이터 세팅
        $pageParam               = [];
        $pageDatas               = [];
        $pageDatas['mobile_num'] = _elm( $memberInfo, 'MB_MOBILE_DEC' );
        $pageDatas['auth_num']   = _elm( $smsResponse, 'auth_num' );
        $pageDatas['mb_idx']     = _elm( $memberInfo, 'IDX' );

        $pageParam['pageDatas']  = $pageDatas;

        $response                = $this->_initApiResponse();
        $response['status']      = 200;
        $response['otp_page']    = $archieView->loadView('Module\login\Views\_otp_auth' , $pageParam );
        $response['alert']       = '등록하신 휴대폰번호로 인증번호가 발송되었습니다.';

        return $this->respond($response, 200);
    }

    public function pushSms( $param )
    {

        //알림톡 데이터 세팅
        $config = new Talk();
        $KakaoInfo         = _elm( $config->talk, 'KakaoInfo' );
        $kakaoTemplateInfo = _elm( $config->talk, 'kakaoTemplate' );




        $kakao = new KakaoLib;
        $talkParam = [];
        $template_code = array_search( _elm( $param, 'temp_name' ), $kakaoTemplateInfo);
        $auth_num = random_string('numeric', 4);

        $kakao_param = [
            'TEMPLATE_CODE' => $template_code,
            'YELLOWID_KEY'  => $KakaoInfo['YELLOWID_KEY'],
            'CALLPHONE'     => _elm($param, 'mobile_num'),
            'REQTIME'       => '00000000000000',
            //'AGENT_FLAG'    => rand(0,4),
            'typeValues'    => [
                'auth_num' => $auth_num
            ]
        ];

        $returnData             = [];
        $returnData['status']   = 200;
        $returnData['auth_num'] = $auth_num;
        return $returnData;

        // try {
        //     $kakao->sendATalk($kakao_param);

        //     $returnData             = [];
        //     $returnData['status']   = 200;
        //     $returnData['auth_num'] = $auth_num;
        //     return $returnData;

        // }
        // //알림톡발송 임시 주석처리
        // catch(\Exception $e) {
        //     $response['status'] = '400';
        //     $response['alert']  = $e->getMessage();

        //    return $this->fail( $e->getMessage(), 401, '303' );
        // }


    }


    public function loginProc(){
        $post = $this->request->getPost();

        if( _elm( $post, 'auth_num' ) != _elm( $post, 'i_auth_number' ) ){
            return $this->respond([ 'status'=>'401', 'alert' => '인증번호가 틀립니다. 다시 시도해주세요.' ]);
        }

        $modelParam = [];
        $modelParam['MB_IDX']       = _elm( $post, 'mb_idx' );
        $loginModel     = new LoginModel();

        $memberInfo     = $loginModel->getUserData( $modelParam );
        // ---------------------------------------------------------------------
        // 로그인 세션처리
        // ---------------------------------------------------------------------
        $datas = [
            '_memberInfo' => [
                'member_idx'   => _elm($memberInfo, 'IDX'),
                'member_name'  => _elm($memberInfo, 'MB_NAME'),
                'member_id'    => _elm($memberInfo, 'MB_UID'),
                'member_level' => _elm($memberInfo, 'MB_LEVEL'),
            ],
        ];
        $this->session->set($datas);

        session_write_close();
        $modelParam['MB_UID']                   = _elm( $memberInfo, 'MB_UID' );
        $modelParam['MB_PASS_ERR_COUNT']        = 0;
        $loginModel->updateLoginCnt( $modelParam );

        $modelParam['MB_LAST_LOGIN_DATETIME']   = date('Y-m-d H:i:s');
        $modelParam['MB_LAST_LOGIN_IP']         = _elm( $_SERVER, 'REMOTE_ADDR' );
        $loginModel->updateLoginDate( $modelParam );



        $response                   = $this->_initApiResponse();
        $response['alert']          = '로그인 되었습니다.';
        $response['status']         = 200;
        $response['redirect_url']   = "/";


        return $this->respond($response, 200);

    }

}