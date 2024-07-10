<?php
#------------------------------------------------------------------
# MenuApi.php
# 메뉴 API
# 김우진
# 2024-06-27 17:29:08
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;
use Module\setting\Models\MenuModel;
use Module\setting\Models\MemberModel;
use App\Libraries\MemberLib;

use Config\Services;
use DOMDocument;
class MenuApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }
    public function writeMenu( $param = [] )
    {
        $response                                   = $this->_initApiResponse();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }


        $requests                                   = $this->request->getPost();

        $menuModel                                  = new MenuModel();

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
                'label'  => '메뉴명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '메뉴명을 입력해주세요.',
                ],
            ],
            'i_link' => [
                'label'  => '링크',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '링크를 입력해주세요.',
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
        # TODO: 메뉴 등록
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['MENU_PARENT_IDX']              = _elm($requests, 'i_parent_idx', 0);
        $modelParam['MENU_GROUP_ID']                = _elm($requests, 'i_group_id', '');
        $modelParam['MENU_NAME']                    = _elm($requests, 'i_name', '');
        $modelParam['MENU_CUSTOM_TAG']              = _elm($requests, 'i_custom_tag', '');
        $modelParam['MENU_LINK']                    = _elm($requests, 'i_link', '');
        $modelParam['MENU_LINK_TARGET']             = _elm($requests, 'i_link_target', '');
        $modelParam['MENU_DISPLAY_MEMBER_GROUP']    = implode(',', _elm($requests, 'i_display_member_group', []));
        $modelParam['MENU_SORT']                    = _elm($requests, 'i_sort', 1);
        $modelParam['MENU_STATUS']                  = _elm($requests, 'i_status', 1);

        $this->db->transBegin();

        $aIdx                                       = $menuModel->writeMenu( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '메뉴 등록 - data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '등록이 완료되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond( $response );

    }
    public function modifyMenu( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $menuModel                                  = new MenuModel();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        if( count( _elm($requests, 'i_menu_idx' ) ) < 1 ){
            $response['status']                     = 400;
            $response['alert']                      = '수정할 메뉴를 선택해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 메뉴 수정
        #------------------------------------------------------------------
        $this->db->transBegin();

        foreach( _elm( $requests, 'i_menu_idx' )  as $vMenuIdx ){
            $aData                                  = $menuModel->getAdminMenuListsByIdx($vMenuIdx);
            $modelParam                             = [];
            $modelParam['MENU_GROUP_ID']            = _elm(_elm($requests, 'i_group_id', []), $vMenuIdx, '');
            $modelParam['MENU_NAME']                = _elm(_elm($requests, 'i_name', []), $vMenuIdx, '');
            $modelParam['MENU_CUSTOM_TAG']          = _elm(_elm($requests, 'i_custom_tag', []), $vMenuIdx, '');
            $modelParam['MENU_LINK']                = _elm(_elm($requests, 'i_link', []), $vMenuIdx, '');
            $modelParam['MENU_LINK_TARGET']         = _elm(_elm($requests, 'i_link_target', []), $vMenuIdx, '_self');
            $modelParam['MENU_DISPLAY_MEMBER_GROUP']= implode(',', _elm(_elm($requests, 'i_display_member_group', []), $vMenuIdx, []));
            $modelParam['MENU_SORT']                = _elm(_elm($requests, 'i_sort', []), $vMenuIdx, 1);
            $modelParam['MENU_STATUS']              = _elm(_elm($requests, 'i_status', []), $vMenuIdx, 1);

            $modelParam['MENU_IDX']                 = $vMenuIdx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aResult                                = $menuModel->updateMenu($modelParam);

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
            $logParam['MB_HISTORY_CONTENT']         = '메뉴 수정 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ). ' // newData::'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);

    }
    public function deleteMenu( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $menuModel                                  = new MenuModel();
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        #------------------------------------------------------------------
        # TODO: 삭제 진행
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['MENU_IDX']                     = _elm( $requests, 'menu_idx' );
        $aData                                      = $menuModel->getAdminMenuListsByIdx( _elm( $requests, 'menu_idx' ) );

        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '메뉴 데이터가 없습니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $aStatus                                    = $menuModel->deleteMenu( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '메뉴 삭제 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/menu' );

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);

    }


}