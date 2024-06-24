<?php
#------------------------------------------------------------------
# Code.php
# 코드 컨트롤러
# 김우진
# 2024-05-28 16:39:08
# @Desc :
#------------------------------------------------------------------
namespace Module\admin\setting\Controllers;
use Module\core\Controllers\ApiController;
use Module\admin\setting\Models\CodeModel;
use Config\Services;
use DOMDocument;


use Exception;

class Code extends ApiController
{
    private $db;
    public function __construct()
    {

        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {

    }
    public function codeLists( $param = [] )
    {
        $response                                 = $this->_initApiResponse();
        $requests                                 = _parseJsonFormData();

        if( empty( $param ) === false ){
            $requests                             = $param;
        }

        $codeModel                                = new CodeModel();

        $modelParam                               = [];
        #------------------------------------------------------------------
        # TODO: 그룹이 있으면 그룹만 뽑아온다.
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_idx' ) ) === false ){

            $modelParam['C_IDX']                  = _elm( $requests, 'u_idx' );
        }
        $codeLists                                = $codeModel->getCodeLists( $modelParam );

        $response['status']                       = 200;
        $response['messages']                     = 'success';
        $menuMergeChild                           = _build_tree( $codeLists, _elm($modelParam, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );


        $response['data']                         = $menuMergeChild;

        if( _elm( $param , 'return_raw' ) === true ){
            return $menuMergeChild;
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '코드 리스트 검색';
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        return $this->respond( $response );

    }

    public function addCode()
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $codeModel                                 = new CodeModel();

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_parent_idx' => [
                'label'  => '상위코드IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위코드IDX',
                ],
            ],
            'u_name' => [
                'label'  => '상위메뉴IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위메뉴IDX',
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

        $modelParam                                = [];
        $modelParam['C_PARENT_IDX']                = _elm( $requests, 'u_parent_idx' );
        $modelParam['C_NAME']                      = _elm( $requests, 'u_name' );

        #------------------------------------------------------------------
        # TODO: 상위 메뉴 존재여부 체크
        #------------------------------------------------------------------

        if( _elm( $requests, 'u_parent_idx' ) != 0 ){
            $findParam                             = [];
            $findParam['C_IDX']                    = _elm( $requests, 'u_parent_idx' );
            $checkCount                            = $codeModel->getCodeOneCnt( $findParam );
            if( $checkCount < 1 ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '이미 삭제되었거나 없는 상위 코드 IDX 입니다. ';

                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 메뉴 중복체크
        #------------------------------------------------------------------
        $sameChecked                               = $codeModel->sameChecked( $modelParam );

        if( $sameChecked > 0 ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '중복된 코드명입니다. 다시 시도해주세요.';

            return $this->respond( $response );
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 자동등록 변수 세팅
        #------------------------------------------------------------------
        $modelParam['C_CODE']                      = _elm( $requests, 'u_code', null, true ) ?? _uniqid( 6, true );
        $modelParam['C_USE_YN']                    = _elm( $requests, 'u_use_yn', null, true ) ?? 'Y';

        #------------------------------------------------------------------
        # TODO: 마지막 번호 뽑아오기
        #------------------------------------------------------------------
        $modelParam['C_SORT']                      = _elm( $requests, 'u_sort', null, true ) ??( ( $codeModel->getLastCodeSortNum( $modelParam ) ?? 0 ) + 1 ) ;


        #------------------------------------------------------------------
        # TODO: 추가 데이터 세팅
        #------------------------------------------------------------------
        $modelParam['C_REG_AT']                    = date( 'Y-m-d H:i:s' );
        $modelParam['C_REG_IP']                    = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['C_REG_MB_IDX']                = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );

        #------------------------------------------------------------------
        # TODO: 데이터 삽입
        #------------------------------------------------------------------

        $this->db->transBegin();

        $mIdx                                      = $codeModel->setCodes( $modelParam );

        if ( $this->db->transStatus() === false || $mIdx === false) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '코드 등록 - 메뉴명: '._elm( $modelParam, 'C_NAME' );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        #------------------------------------------------------------------
        # TODO: 결과값 리턴
        #------------------------------------------------------------------

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        #------------------------------------------------------------------
        # TODO: 데이터 재검색
        #------------------------------------------------------------------
        $aParam                                    = [];
        $aParam['return_raw']                      = true;
        $aParam['u_idx']                           = _elm( $requests, 'u_idx' );
        $response['data']                          = $this->codeLists($aParam);


        return $this->respond( $response );

    }

    public function modifyCode( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $codeModel                                 = new CodeModel();

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_idx' => [
                'label'  => '코드IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '코드IDX 누락',
                ],
            ],
            'u_parent_idx' => [
                'label'  => '상위코드IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위코드IDX 누락',
                ],
            ],

            'u_name' => [
                'label'  => '코드명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '코드명 누락',
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

        $modelParam                                = [];

        $modelParam['C_IDX']                       = _elm( $requests, 'u_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $aData                                     = $codeModel->getCodeInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }


        $modelParam['C_PARENT_IDX']                = _elm( $requests, 'u_parent_idx' );
        $modelParam['C_NAME']                      = _elm( $requests, 'u_name' );

        #------------------------------------------------------------------
        # TODO: 코드명이 바뀌면
        #------------------------------------------------------------------
        if( _elm( $aData, 'C_NAME' ) != _elm( $modelParam, 'C_NAME' ) ){

            #------------------------------------------------------------------
            # TODO: 코드 중복체크
            #------------------------------------------------------------------

            $sameChecked                           = $codeModel->sameChecked( $modelParam );

            if( $sameChecked > 0 ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '중복된 코드명입니다. 다시 시도해주세요.';

                return $this->respond( $response );
                exit;
            }

        }

        #------------------------------------------------------------------
        # TODO: 자동등록 변수 세팅
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_code' ) ) === false && _elm( $aData, 'C_CODE' ) != _elm( $requests, 'u_code' ) ){
            #------------------------------------------------------------------
            # TODO: 코드 중복체크
            #------------------------------------------------------------------
            #------------------------------------------------------------------
            # TODO: 잠시 데이터 unset
            #------------------------------------------------------------------
            unset( $modelParam['C_NAME'] );

            $modelParam['C_CODE']                  = _elm( $requests, 'u_code' );

            $sameChecked                           = $codeModel->sameChecked( $modelParam );

            if( $sameChecked > 0 ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '중복된 코드입니다. 다시 시도해주세요.';

                return $this->respond( $response );
                exit;
            }

            #------------------------------------------------------------------
            # TODO: 다시 데이터 세팅
            #------------------------------------------------------------------
            $modelParam['C_NAME']                  = _elm( $requests, 'u_name' );
        }

        $modelParam['C_USE_YN']                    = _elm( $requests, 'u_use_yn', null, true ) ?? 'Y';

        #------------------------------------------------------------------
        # TODO: 추가 데이터 세팅
        #------------------------------------------------------------------
        $modelParam['C_UP_AT']                     = date( 'Y-m-d H:i:s' );
        $modelParam['C_UP_IP']                     = _elm( $_SERVER, 'REMOTE_ADDR' );
        $modelParam['C_UP_MB_IDX']                 = _elm( _elm( $GLOBALS, 'userInfo' ), 'MB_IDX' );



        if( _elm( $requests, 'u_sort' ) != _elm( $aData, 'C_SORT' ) ){
            $modelParam['C_SORT']                  = _elm( $requests, 'u_sort' );

            $codeModel->reSort( _elm( $modelParam, 'C_PARENT_IDX' ), _elm( $aData, 'C_SORT' ), _elm( $modelParam, 'C_SORT' ) );

        }

        #------------------------------------------------------------------
        # TODO: 데이터 업데이트
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aResult                                   = $codeModel->patchCode( $modelParam );

        if ( $this->db->transStatus() === false | $aResult === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = 'CODE 정보 수정 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL."=> newData:".json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------



        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        #------------------------------------------------------------------
        # TODO: 데이터 재검색
        #------------------------------------------------------------------
        $aParam                                    = [];
        $aParam['return_raw']                      = true;
        $aParam['u_idx']                           = _elm( $requests, 'u_idx' );
        $response['data']                          = $this->codeLists($aParam);


        return $this->respond( $response );

    }

    public function deleteCode()
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();
        $codeModel                                 = new CodeModel();
        $validation                                = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_idx' => [
                'label'  => '코드IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '코드IDX 누락',
                ],
            ],
        ]);

        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ($validation->run($requests) === false) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }


        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $modelParam                                = [];
        $modelParam['C_IDX']                       = _elm( $requests, 'u_idx' );
        $aData                                     = $codeModel->getCodeInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 코드 삭제 시 하위 코드도 삭제
        #------------------------------------------------------------------
        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 재귀호출로 인하여 response 값은  deleteCodeAndChildren 함수에서 세팅
        #------------------------------------------------------------------
        $aResult = $this->deleteCodeAndChildren($requests['u_idx']);

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = 'CODE 삭제 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        if ($this->db->transStatus() === false || $aResult['status'] === 400) {
            $this->db->transRollback();
            $response                              = $aResult;
            return $this->respond($response);
        } else {
            $this->db->transCommit();
            $response                              = $aResult;
            return $this->respond($aResult);
        }
    }

    private function deleteCodeAndChildren($codeIdx)
    {
        $codeModel                                 = new CodeModel();

        #------------------------------------------------------------------
        # TODO: 하위메뉴 가져옴
        #------------------------------------------------------------------
        $childMenus                                = $codeModel->getChildCodes($codeIdx);

        // 만약 getChildMenus가 실패하면 빈 배열로 처리
        if ($childMenus === false) {
            $childMenus                            = [];
        }

        #------------------------------------------------------------------
        # TODO: 하위 메뉴가 있으면 재귀적으로 삭제
        #------------------------------------------------------------------
        foreach ($childMenus as $childMenu) {
            $loopResult                            = $this->deleteCodeAndChildren($childMenu['C_IDX']);

            if ($loopResult['status'] === 400) {
                return $loopResult;
            }
        }

        #------------------------------------------------------------------
        # TODO: 현재 메뉴 삭제
        #------------------------------------------------------------------
        if (!$codeModel->deleteCode($codeIdx)) {
            return [
                'status' => 400,
                'error' => 400,
                'messages' => '현재 코드 삭제 중 오류 발생'
            ];
        } else {
            return [
                'status' => 200,
                'messages' => 'success'
            ];
        }
    }


}
