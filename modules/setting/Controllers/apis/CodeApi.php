<?php
#------------------------------------------------------------------
# CodeApi.php
# code API
# 김우진
# 2024-07-23 11:12:27
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;
use Module\setting\Models\CodeModel;

use App\Libraries\MemberLib;

use Config\Services;
use DOMDocument;
class CodeApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $codeModel;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->codeModel                            = new CodeMOdel();
    }
    public function writeCode( $param = [] )
    {
        $response                                   = $this->_initApiResponse();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }


        $requests                                   = $this->request->getPost();

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
            'i_name' => [
                'label'  => '코드명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '코드명을 입력해주세요.',
                ],
            ],
            'i_parent_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX를 선택해주세요.',
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

        $modelParam                                = [];
        $modelParam['C_PARENT_IDX']                = _elm( $requests, 'i_parent_idx' );
        $modelParam['C_NAME']                      = _elm( $requests, 'i_name' );

        #------------------------------------------------------------------
        # TODO: 상위 메뉴 존재여부 체크
        #------------------------------------------------------------------

        if( _elm( $requests, 'u_parent_idx' ) != 0 ){
            $findParam                             = [];
            $findParam['C_IDX']                    = _elm( $requests, 'u_parent_idx' );
            $checkCount                            = $this->codeModel->getCodeOneCnt( $findParam );
            if( $checkCount < 1 ){
                $response['status']                = 400;
                $response['alert']                 = '이미 삭제되었거나 없는 상위 코드 IDX 입니다.';
                return $this->respond( $response, 400 );
            }
        }

        #------------------------------------------------------------------
        # TODO: 코드 중복체크
        #------------------------------------------------------------------
        $sameChecked                               = $this->codeModel->sameChecked( $modelParam );
        if( $sameChecked > 0 ){
            $response['status']                    = 400;
            $response['alert']                     = '중복된 코드명입니다. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 코드 등록
        #------------------------------------------------------------------

        #------------------------------------------------------------------
        # TODO: 자동등록 변수 세팅
        #------------------------------------------------------------------
        $modelParam['C_CODE']                      = _elm( $requests, 'i_code', null, true ) ?? _uniqid( 6, true );
        $modelParam['C_STATUS']                    = _elm( $requests, 'i_status', null, true ) ?? 0;
        $modelParam['C_SORT']                      = _elm( $requests, 'i_sort', null, true ) ?? 1;

        #------------------------------------------------------------------
        # TODO: 추가 데이터 세팅
        #------------------------------------------------------------------
        $modelParam['C_REG_AT']                    = date( 'Y-m-d H:i:s' );
        $modelParam['C_REG_IP']                    = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['C_REG_MB_IDX']                = _elm( $this->session->get('_memberInfo') , 'member_idx' );


        $this->db->transBegin();

        $aIdx                                       = $this->codeModel->writeCode( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['messages']                   = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '코드 등록 - data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();

        $response                                   = $this->_unset( $response );
        $response['status']                         = 200;
        $response['alert']                          = '등록이 완료되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond( $response );

    }
    public function modifyCode( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        if( count( _elm($requests, 'i_code_idx' ) ) < 1 ){
            $response['status']                     = 400;
            $response['alert']                      = '수정할 코드를 선택해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 메뉴 수정
        #------------------------------------------------------------------
        $this->db->transBegin();

        foreach( _elm( $requests, 'i_code_idx' )  as $vCodeIdx ){
            $aData                                  = $this->codeModel->getCodesByIdx($vCodeIdx);
            $modelParam                             = [];
            $modelParam['C_NAME']                   = _elm(_elm($requests, 'i_name', []), $vCodeIdx, '');
            $modelParam['C_SORT']                   = _elm(_elm($requests, 'i_sort', []), $vCodeIdx, 1);
            $modelParam['C_STATUS']                 = _elm(_elm($requests, 'i_status', []), $vCodeIdx, 1);

            $modelParam['C_IDX']                    = $vCodeIdx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aResult                                = $this->codeModel->updateCode($modelParam);

            if ( $this->db->transStatus() === false || $aResult === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['messages']               = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response, 400 );
            }

            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = 'zhem 수정 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ). ' // newData::'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

        }

        $this->db->transCommit();
        $response                                   = $this->_unset( $response );

        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }


        return $this->respond($response);

    }
    public function deleteCode( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        #------------------------------------------------------------------
        # TODO: 삭제 진행
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['C_IDX']                        = _elm( $requests, 'code_idx' );
        $aData                                      = $this->codeModel->getCodesByIdx( _elm( $requests, 'code_idx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '코드 데이터가 없습니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $aStatus                                    = $this->codeModel->deleteCode( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '코드 삭제 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();
        $response                                   = $this->_unset( $response );
        $response['status']                         = 200;
        $response['alert']                          = '삭제되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);

    }


}