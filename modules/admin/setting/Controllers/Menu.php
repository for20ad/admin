<?php
#------------------------------------------------------------------
# Menu.php
# 사용자메뉴
# 김우진
# 2024-05-21 14:53:31
# @Desc : 사용자메뉴
#------------------------------------------------------------------

namespace Module\admin\setting\Controllers;
use Module\core\Controllers\ApiController;
use Module\admin\setting\Models\SettingModel;
use Config\Services;
use DOMDocument;

class Menu extends ApiController
{
    private $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function menuLists( $param = [] )
    {
        $response                                 = $this->_initApiResponse();
        $requests                                 = _parseJsonFormData();

        if( empty( $param ) === false ){
            $requests                             = $param;
        }

        $settingModel                             = new SettingModel();

        $modelParam                               = [];
        #------------------------------------------------------------------
        # TODO: 그룹이 있으면 그룹만 뽑아온다.
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'u_group' ) ) === false ){
            $modelParam['MENU_GROUP']             = _elm( $requests, 'u_group' );
        }
        $menuLists                                = $settingModel->getMenuLists( $modelParam );

        $response['status']                       = 200;
        $response['messages']                     = 'success';
        $menuMergeChild                           = _build_tree( $menuLists );

        $response['data']                         = $menuMergeChild;

        if( _elm( $param , 'return_raw' ) === true ){
            return $menuMergeChild;
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = 'FRONT 메뉴 검색';
        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        return $this->respond( $response );

    }

    public function addMenu()
    {

        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();
        $settingModel                              = new SettingModel();
        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_menu_parent_idx' => [
                'label'  => '상위메뉴IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위메뉴IDX 누락',
                ],
            ],
            'u_menu_group' => [
                'label'  => '메뉴그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴그룹값 누락',
                ],
            ],
            'u_menu_name' => [
                'label'  => '메뉴명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴명 누락',
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



        $modelParam                                         = [];
        $modelParam['MENU_PARENT_IDX']                      = _elm( $requests, 'u_menu_parent_idx' );
        $modelParam['MENU_GROUP']                           = _elm( $requests, 'u_menu_group' );
        $modelParam['MENU_NAME']                            = _elm( $requests, 'u_menu_name' );

        #------------------------------------------------------------------
        # TODO: 상위 메뉴 존재여부 체크
        #------------------------------------------------------------------

        if( _elm( $requests, 'u_menu_parent_idx' ) != 0 ){
            $findParam                                               = [];
            $findParam['MENU_IDX']                                   = _elm( $requests, 'u_menu_parent_idx' );
            $checkCount                                              = $settingModel->getMenuOneCnt( $findParam );
            if( $checkCount < 1 ){
                $response['status']                                  = 400;
                $response['error']                                   = 400;
                $response['messages']                                = '이미 삭제되었거나 없는 메뉴 IDX 입니다. ';

                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 메뉴 중복체크
        #------------------------------------------------------------------
        $sameChecked                                        = $settingModel->sameChecked( $modelParam );

        if( $sameChecked > 0 ){
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = '중복된 메뉴입니다. 다시 시도해주세요.';

            return $this->respond( $response );
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 옵션 변수 세팅
        #------------------------------------------------------------------
        $modelParam['MENU_CUSTOM_TAG']                      = _elm( $requests, 'u_menu_custom_tag' );
        $modelParam['MENU_LINK']                            = _elm( $requests, 'u_menu_link' );
        $modelParam['MENU_LINK_TARGET']                     = _elm( $requests, 'u_menu_link_target' );

        #------------------------------------------------------------------
        # TODO: 마지막 번호 뽑아오기
        #------------------------------------------------------------------

        $sort                                               = $settingModel->getLastMenuSortNum( $modelParam );
        $modelParam['MENU_SORT']                            = ( $sort ?? 0 ) + 1;

        #------------------------------------------------------------------
        # TODO: 데이터 삽입
        #------------------------------------------------------------------

        $this->db->transBegin();

        $mIdx                                               = $settingModel->setFrontMenu( $modelParam );

        if ( $this->db->transStatus() === false || $mIdx === false) {
            $this->db->transRollback();
            $response['status']                              = 400;
            $response['messages']                            = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = 'FRONT 메뉴 등록 - 메뉴명: '._elm( $modelParam, 'MENU_NAME' );
        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        #------------------------------------------------------------------
        # TODO: 결과값 리턴
        #------------------------------------------------------------------

        $response['status']                                  = 200;
        $response['messages']                                = 'success';

        #------------------------------------------------------------------
        # TODO: 데이터 재검색
        #------------------------------------------------------------------
        $aParam                                              = [];
        $aParam['return_raw']                                = true;
        $aParam['group']                                     = _elm( $requests, 'u_menu_group' );
        $response['data']                                    = $this->menuLists($aParam);


        return $this->respond( $response );

    }

    public function modifyMenu()
    {

        $response                                            = $this->_initApiResponse();
        $requests                                            = _parseJsonFormData();



        $settingModel                                        = new SettingModel();

        $validation                                          = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_menu_idx' => [
                'label'  => '메뉴IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴IDX 누락',
                ],
            ],
            'u_menu_parent_idx' => [
                'label'  => '상위메뉴IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위메뉴IDX 누락',
                ],
            ],
            'u_menu_group' => [
                'label'  => '메뉴그룹',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴그룹값 누락',
                ],
            ],
            'u_menu_name' => [
                'label'  => '메뉴명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴명 누락',
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

        $modelParam                                         = [];

        $modelParam['MENU_IDX']                             = _elm( $requests, 'u_menu_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $aData                                              = $settingModel->getMenuInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }


        $modelParam['MENU_PARENT_IDX']                      = _elm( $requests, 'u_menu_parent_idx' );
        $modelParam['MENU_GROUP']                           = _elm( $requests, 'u_menu_group' );
        $modelParam['MENU_NAME']                            = _elm( $requests, 'u_menu_name' );

        #------------------------------------------------------------------
        # TODO: 메뉴명이 바뀌면
        #------------------------------------------------------------------
        if( _elm( $aData, 'MENU_NAME' ) != _elm( $modelParam, 'MENU_NAME' ) ){

            #------------------------------------------------------------------
            # TODO: 메뉴 중복체크
            #------------------------------------------------------------------
            $sameChecked                                        = $settingModel->sameChecked( $modelParam );

            if( $sameChecked > 0 ){
                $response['status']                             = 400;
                $response['error']                              = 400;
                $response['messages']                           = '중복된 메뉴입니다. 다시 시도해주세요.';

                return $this->respond( $response );
                exit;
            }

        }

        #------------------------------------------------------------------
        # TODO: 옵션 변수 세팅
        #------------------------------------------------------------------
        $modelParam['MENU_CUSTOM_TAG']                      = _elm( $requests, 'u_menu_custom_tag' );
        $modelParam['MENU_LINK']                            = _elm( $requests, 'u_menu_link' );
        $modelParam['MENU_LINK_TARGET']                     = _elm( $requests, 'u_menu_link_target' );

        #------------------------------------------------------------------
        # TODO: 정렬 순서가 틀리면 재정렬
        #------------------------------------------------------------------
        if( _elm( $requests, 'u_menu_sort' ) != _elm( $aData, 'MENU_SORT' ) ){
            $modelParam['MENU_SORT']                        = _elm( $requests, 'u_menu_sort' );

            $settingModel->reSort( _elm( $modelParam, 'MENU_PARENT_IDX' ), _elm( $aData, 'MENU_SORT' ), _elm( $modelParam, 'MENU_SORT' ) );

        }

        #------------------------------------------------------------------
        # TODO: 데이터 업데이트
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aResult                                            = $settingModel->patchFrontMenu( $modelParam );

        if ( $this->db->transStatus() === false | $aResult === false ) {
            $this->db->transRollback();
            $response['status']                             = 400;
            $response['messages']                           = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = 'FRONT 메뉴 수정 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL."=> newData:".json_encode( $modelParam, JSON_UNESCAPED_UNICODE );

        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------



        $this->db->transCommit();

        $response['status']                                 = 200;
        $response['messages']                               = 'success';

        #------------------------------------------------------------------
        # TODO: 데이터 재검색
        #------------------------------------------------------------------
        $aParam                                             = [];
        $aParam['return_raw']                               = true;
        $aParam['group']                                    = _elm( $requests, 'u_menu_group' );
        $response['data']                                   = $this->menuLists($aParam);


        return $this->respond( $response );

    }


    public function deleteMenu()
    {
        $response                                               = $this->_initApiResponse();
        $requests                                               = _parseJsonFormData();
        $settingModel                                           = new SettingModel();
        $validation                                             = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'u_menu_idx' => [
                'label'  => '메뉴IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴IDX 누락',
                ],
            ],
        ]);

        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ($validation->run($requests) === false) {
            $response['status']                                 = 400;
            $response['error']                                  = 400;
            $response['messages']                               = $validation->getErrors();

            return $this->respond($response, 400);
        }


        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $modelParam                                         = [];
        $modelParam['MENU_IDX']                             = _elm( $requests, 'u_menu_idx' );
        $aData                                              = $settingModel->getMenuInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                             = 400;
            $response['error']                              = 400;
            $response['messages']                           = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 상위 메뉴 삭제 시 하위 메뉴도 삭제되도록
        #------------------------------------------------------------------
        $this->db->transBegin();

        #------------------------------------------------------------------
        # TODO: 재귀호출로 인하여 response 값은  deleteMenuAndChildren 함수에서 세팅
        #------------------------------------------------------------------
        $aResult = $this->deleteMenuAndChildren($requests['u_menu_idx']);

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                            = [];
        $logParam['MB_HISTORY_CONTENT']                      = 'FRONT 메뉴 삭제 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                                  = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        if ($this->db->transStatus() === false || $aResult['status'] === 400) {
            $this->db->transRollback();
            $response                                            = $aResult;
            return $this->respond($response);
        } else {
            $this->db->transCommit();
            $response                                            = $aResult;
            return $this->respond($aResult);
        }
    }

    private function deleteMenuAndChildren($menuIdx)
    {
        $settingModel                                           = new SettingModel();

        #------------------------------------------------------------------
        # TODO: 하위메뉴 가져옴
        #------------------------------------------------------------------
        $childMenus                                             = $settingModel->getChildMenus($menuIdx);

        // 만약 getChildMenus가 실패하면 빈 배열로 처리
        if ($childMenus === false) {
            $childMenus                                         = [];
        }

        #------------------------------------------------------------------
        # TODO: 하위 메뉴가 있으면 재귀적으로 삭제
        #------------------------------------------------------------------
        foreach ($childMenus as $childMenu) {
            $loopResult                                         = $this->deleteMenuAndChildren($childMenu['MENU_IDX']);

            if ($loopResult['status'] === 400) {
                return $loopResult;
            }
        }

        #------------------------------------------------------------------
        # TODO: 현재 메뉴 삭제
        #------------------------------------------------------------------
        if (!$settingModel->deleteMenu($menuIdx)) {
            return [
                'status' => 400,
                'error' => 400,
                'messages' => '현재 메뉴 삭제 중 오류 발생'
            ];
        } else {
            return [
                'status' => 200,
                'messages' => 'success'
            ];
        }
    }

}
