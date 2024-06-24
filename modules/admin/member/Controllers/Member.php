<?php

#------------------------------------------------------------------
# Member.php
# 관리자 Member컨트롤러
# 김우진
# 2024-05-17 14:22:02
# @Desc : 관리자 전용 member controller
#------------------------------------------------------------------

namespace Module\admin\member\Controllers;


use Module\core\Controllers\ApiController;
use Module\comm\member\Models\MemberModel;
use Module\auth\Models\AuthModel;
use Config\Services;
use Config\Talk;

use App\Libraries\KakaoLib;

use DOMDocument;


use Exception;

class Member extends ApiController
{

    public function __construct()
    {

        parent::__construct();
    }
    public function index()
    {

    }

    #------------------------------------------------------------------
    # TODO: 회원 가입부 부터 작성
    #------------------------------------------------------------------
    public function registerStepOne()
    {
        $response                   = $this->_initApiResponse();
        print_r( $response );
    }
    public function registerStepTwo()
    {

    }

    #------------------------------------------------------------------
    # TODO: 회원검색, 아이디 찾기
    #------------------------------------------------------------------
    public function findUserId()
    {
        $response                               = $this->_initApiResponse();
        $requests                               = _parseJsonFormData();
        $memberModel                            = new memberModel();
        $authModel                              = new authModel();


        if( empty( _elm( $requests, 'mobileNo' ) ) === true ){
            $response['status']                 = 400;
            $response['error']                  = 400;
            $response['messages']               = '휴대폰번호가 없습니다.';

            return $this->respond($response); //4xx
            exit();
        }

        #------------------------------------------------------------------
        # TODO: 회원테이블에 휴대폰으로 검색 후 휴대폰 번호가 존재하면 알림톡 발송
        #------------------------------------------------------------------
        $modelParam                             = [];
        $modelParam['mobileNo']                 = $this->_aesEncrypt( _elm( $requests, 'mobileNo' ) );

        $chkInfo                                = $memberModel->findUserByMobile( $modelParam );

        #------------------------------------------------------------------
        # TODO: 검색결과가 없으면 리턴
        #------------------------------------------------------------------
        if( empty( $chkInfo ) === true ){
            $response['status']                 = 201;
            $response['messages']               = '검색되는 휴대폰 번호가 없습니다.';

            return $response;
            exit();
        }

        $authFindParam                          = [];
        $authFindParam['MB_IDX']                = _elm( $chkInfo, 'MB_IDX' );


        $talkParam                              = [];

        $kakao                                  = new KakaoLib;
        //알림톡 데이터 세팅
        $config                                 = new Talk();
        $KakaoInfo                              = _elm( $config->talk, 'KakaoInfo' );
        $kakaoTemplateInfo                      = _elm( $config->talk, 'kakaoTemplate' );

        $template_name                          = '인증번호3분';

        $template_code                          = array_search( _elm( $requests, 'temp_name' ), $kakaoTemplateInfo);


        $auth_num                               = random_string('numeric', 4);

        $kakao_param = [
            'TEMPLATE_CODE'                     => $template_code,
            'YELLOWID_KEY'                      => $KakaoInfo['YELLOWID_KEY'],
            'CALLPHONE'                         => _elm( $requests, 'mobileNo'),
            'REQTIME'                           => '00000000000000',
            //'AGENT_FLAG'    => rand(0,4),
            'typeValues'                        => [
                'auth_num'                      => $auth_num
            ]
        ];


        #------------------------------------------------------------------
        # TODO: 만료이전의 인증번호가 있으면 인증번호 재사용
        #------------------------------------------------------------------
        $authData                               = $authModel->getNoExpireAuthKey( $authFindParam );

        if( empty( $authData ) === false  ){
            $auth_num                           = _elm( $authData, 'MB_AK_KEY' );
            $kakao_param['typeValues']['auth_num'] = $auth_num;

            $authUpDateParam                    = [];
            $authUpDateParam['MB_AK_IDX']       = _elm( $authData, 'MB_AK_IDX' );
            $updateAuthData                     = $authModel->updateAuthData( $authUpDateParam );

        }

        #------------------------------------------------------------------
        # TODO: 인증번호의 암호화 방식은 다른방식으로 변경
        #------------------------------------------------------------------

        $crypto                                 = new \App\Helpers\CryptoHelper();
        // 데이터 암호화
        $encrypted_data                         = $crypto->encrypt($auth_num);

        #------------------------------------------------------------------
        # TODO: 알림톡으로 인증번호 보내기
        #------------------------------------------------------------------

        try {
            $kakao->sendATalk($kakao_param);


            $response['status']                 = 200;
            $response['messages']               = '메시지발송이 완료되었습니다. 3분안에 인증을 완료해주세요.';
            $response['data']['auth_num_enc']   = $encrypted_data;
            // $returnData['data']['auth_num']     = $auth_num;
            return $this->respond( $response );


        }
        //알림톡발송 임시 주석처리
        catch(\Exception $e) {
            $response['status']                 = 400;
            $response['error']                  = 400;
            $response['messages']               = $e->getMessage();

            return $this->respond( $response );
        }


    }


}
