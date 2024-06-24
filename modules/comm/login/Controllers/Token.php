<?php
#------------------------------------------------------------------
# Token.php
# 토큰API
# 김우진
# 2024-05-13 09:04:17
# @Desc : 토큰 관련 컨트롤러
#------------------------------------------------------------------

namespace Module\comm\login\Controllers;

use Module\comm\member\Models\MemberModel;
use Module\token\Models\TokenModel;
use Module\core\Controllers\ApiController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\LicenseLib;
use Exception;

class Token extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('jwt');
    }

    public function generateToken( $postData )
    {
        try {
            $requests = $postData;
            $memberModel = new MemberModel();

            $requests['u_pwd_enc'] =  $this->_aesEncrypt( _elm($requests , 'u_pwd' ) );
            $userInfo = $memberModel->findUserById( $requests );

            if( empty( $userInfo ) === true ){

                $return_arr = [
                    'status' => 201,
                    'messages'=> '아이디 또는 비밀번호를 정확히 입력해주세요.',
                ];
            }else{

                if( _elm( $userInfo, 'MB_STATUS' ) === '3' || _elm( $userInfo, 'MB_STATUS' ) === '4' ) {
                    $return_arr = [
                        'status' => 201,
                        'messages'=> '이미 탈퇴되었거나 삭제된 회원입니다.'
                    ];
                }else{
                    #------------------------------------------------------------------
                    # TODO: snslogin이 아닐 경우 패스워드가 예전 패스워드면 재설정 진행.
                    #------------------------------------------------------------------
                    if( _elm( $requests, 'u_pwd' ) != 'snsSignin' && _elm( $requests, 'pass_login' ) !== true ){
                        if(_elm($userInfo, 'PASSWD_CHK') !== '1') {
                            $modelParam                         = [];
                            $modelParam['IDX']                  = _elm( $userInfo, 'IDX' );
                            $modelParam['MB_PASSWORD']          = $this->_aesEncrypt( _elm($requests , 'u_pwd' ) );
                            $modelParam['PASSWD_CHK']           = 1;

                            $bStatus                    = $memberModel->updateMember( $modelParam );
                            if ($bStatus === false) {
                                $return_arr = [
                                    'status' => 400,
                                    'messages'=> '비밀번호 해싱 업데이트 오류 발생.'
                                ];
                            }
                        }

                        // 변경된 비밀번호 데이터를 위해 다시 한번 회원 정보 조회
                        $reFindParam = [];
                        $reFindParam['u_id']                    = _elm( $requests, 'u_id' );
                        $reFindParam['u_pwd']                   =  $this->_aesEncrypt( _elm( $requests, 'u_pwd' ) );
                        $reFindParam['u_pwd_enc'] =  $this->_aesEncrypt( _elm($requests , 'u_pwd' ) );

                        $userInfo                               = $memberModel->findUserById( $reFindParam );
                    }


                    // 휴대폰번호 디코딩
                    if(empty(_elm($userInfo, 'MB_MOBILE_NUM')) === false) {
                        $userInfo['MB_MOBILE_NUM'] = $this->_aesDecrypt( _elm($userInfo, 'MB_MOBILE_NUM') );
                    }

                    $access_token = setAccessToken( $userInfo );

                    if( _elm( $requests, 'isAutoSignin' ) === 'true' ) {
                        $userInfo['isAutoSignin'] = 'true';
                    }else{
                        $userInfo['isAutoSignin'] = 'false';
                    }

                    $refresh_token = setRefreshToken( $userInfo );

                    $return_arr = [
                        'status' => 200,
                        'messages' => 'success',
                        'data' => ['userinfo'=>$userInfo,'access_token' => $access_token,'refresh_token'=> $refresh_token]
                    ];


                    // ---------------------------------
                    // 로그인 업데이트
                    // ---------------------------------
                    $updateParam                        = [];
                    $updateParam['MB_IDX']              = _elm( $userInfo, 'MB_IDX' );
                    $updateParam['MB_LAST_LOGIN']       = date( 'Y-m-d H:i:s' );
                    $updateParam['MB_LAST_LOGIN_IP']    = _elm( $_SERVER, 'REMOTE_ADDR' );
                    $updateParam['MB_LOGIN_CNT']        = _elm( $userInfo, 'MB_LOGIN_CNT' ) + 1;

                    $memberModel->updateLastLogin( $updateParam );

                    // ---------------------------------
                    // TOKEN 저장
                    // ---------------------------------
                    $tokenModel = new TokenModel();

                    $tokenParam                        = [];
                    $tokenParam['REFRESH_TOKEN']       = $refresh_token;
                    $tokenParam['MB_USERID']           = _elm($userInfo, 'MB_USERID');
                    $tokenParam['MB_PASSWORD']         = _elm($userInfo, 'MB_PASSWORD');
                    $tokenParam['IP_ADDRESS']          = _elm( $_SERVER, 'REMOTE_ADDR' );
                    $tokenParam['REG_AT']              = date( 'Y-m-d H:i:s' );

                    $tokenModel->registRefreshToken( $tokenParam );
                }
            }

            return $return_arr ;
        } catch (Exception $ex) {
            return $this->fail(
                [
                    'status' => 400,
                    'error' => 400,
                    'messages'['error'] => $ex->getMessage()
                ],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
    public function generateForceToken( $postData )
    {
        try {
            $requests = $postData;
            $model = new MemberModel();
            $userInfo = [];

            $userInfo['MB_USERID'] = _elm( $requests, 'u_id' );




            if( empty( _elm( $userInfo , 'MB_USERID' ) ) === true  ){

                $return_arr = [
                    'status' => 201,
                    'messages'=> '아이디누락..!!!!',
                ];
            }else{

                $access_token = setAccessToken( $userInfo );



                $refresh_token = setRefreshToken( $userInfo );

                $return_arr = [
                    'status' => 200,
                    'messages' => 'success',
                    'data' => ['userinfo'=>$userInfo,'access_token' => $access_token,'refresh_token'=> $refresh_token]
                ];
            }


            return $return_arr ;
        } catch (Exception $ex) {
            return $this->fail(
                [
                    'status' => 400,
                    'error' => 400,
                    'messages'['error'] => $ex->getMessage()
                ],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
}

