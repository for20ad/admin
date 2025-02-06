<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\ExcelFormModel;
use Module\setting\Models\MenuModel;
use Module\setting\Config\Config as settingConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class ExcelFormApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }

    public function getFormLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $excelFormModel                             = new ExcelFormModel();
        $requests                                   = _trim($this->request->getPost());
        $aConfig                                    = new settingConfig();
        $aConfig                                    = $aConfig->menu;

        if (empty( _elm($param, 'post') ) === false)
        {
            $requests                               = _elm($param, 'post');
        }

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }



        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        #------------------------------------------------------------------
        # TODO:  리스트 가져오기
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['F_MENU']                       = _elm( $requests, 's_forms' );
        $modelParam['F_STATUS']                     = _elm( $requests, 's_status' );


        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_USERNAME']      = _elm( $requests , 's_keyword' );
                    break;
                case 'title' :
                    $modelParam['F_TITLE']          = _elm( $requests , 's_keyword' );
                    break;
            }
        }

        switch(  _elm( $requests , 'ordering' ) )
        {
            case 'regdate_desc' :
                $modelParam['order']                = ' F_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' F_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' F_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' F_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' F_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;



        $aLISTS_RESULT                              = $excelFormModel->getExcelFormLists($modelParam);

        $total_count                                = _elm($aLISTS_RESULT, 'total_count', 0);

        $page_datas                                 = [];


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $list_result                            = _elm( $aLISTS_RESULT , 'lists', [] );

            $view_datas['aConfig']                  = $aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\setting\Views\download\form_lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/setting/excelFormLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function formDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'formIdx' ) ) === true ){
            $response['status']                               = 400;
            $response['alert']                                = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $excelFormModel                             = new ExcelFormModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $aConfig->menu;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['F_IDX']                        = _elm( $requests, 'formIdx' );
        $aData                                      = $excelFormModel->getFormsDataByIdx( $modelParam );
        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '폼데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $keys                                       = _elm( $aData, 'F_MENU' );

        $view_datas['fields']                       = _elm($this->sharedConfig::$excelField,$keys );
        $view_datas['aData']                        = $aData;
        //print_r( $view_datas );

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\setting\Views\download\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function formRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $excelFormModel                             = new ExcelFormModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $aConfig->menu;

        #------------------------------------------------------------------
        # TODO: 키의 0번째를 가져와 데이터 출력
        #------------------------------------------------------------------
        $keys                                       = array_keys( _elm( _elm($view_datas, 'aConfig'), 'forms' ) );

        $view_datas['fields']                       = _elm($this->sharedConfig::$excelField, _elm( $keys, 0));


        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\setting\Views\download\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function getFormFileds()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $excelFormModel                             = new ExcelFormModel();



        $aConfig                                    = $aConfig->menu;

        $keys                                       = array_keys( _elm( $aConfig, 'forms' ) );


        $fields                                     = _elm($this->sharedConfig::$excelField, _elm( $requests, 'form' ) );

        $response['status']                         = 200;
        $response['fields']                         = $fields;

        return $this->respond( $response );

    }

    public function saveForm()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $excelFormModel                             = new ExcelFormModel();
        $menuModel                                  = new MenuModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_title' => [
                'label'  => '양식명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '양식명을 입력하세요.',
                ],
            ],
            'i_forms' => [
                'label'  => '메뉴분류',
                'rules'  => 'required',
                'errors' => [
                    'required' => '메뉴분류를 입력하세요.',
                ],
            ],
            'i_field' => [
                'label'  => '필드',
                'rules'  => 'required',
                'errors' => [
                    'required' => '필드를 선택하세요.',
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
        # TODO: 메뉴데이터 로드하여 데이터세팅
        #------------------------------------------------------------------
        $menuData                                   = $menuModel->getMenuDataByPrefix( preg_replace( '/_/', '', _elm( $requests, 'i_forms' ) ) );
        if( empty( $menuData ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['alert']                      = '존재하지 않는 메뉴데이터입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }



        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['F_TITLE']                      = _elm( $requests, 'i_title' );
        $modelParam['F_MENU']                       = _elm( $requests, 'i_forms' );
        $modelParam['F_LOCATION']                   = _elm( $menuData, 'MENU_LINK' );

        $modelParam['F_FIELDS']                     = join("|", _elm( $requests, 'i_field' ) );


        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: 데이터 추가 run
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'i_idx' ) ) === true ){
            #------------------------------------------------------------------
            # TODO: max sort 가져오기
            #------------------------------------------------------------------
            $max_sort                                   = $excelFormModel->getFormsMaxSort( _elm( $requests, 'i_forms' ) );
            $modelParam['F_SORT']                       = $max_sort + 1;
            $modelParam['F_STATUS']                     = 'Y';
            $modelParam['F_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
            $modelParam['F_CREATE_IDX']                 = _elm( $this->session->get('_memberInfo') , 'member_idx' );
            $modelParam['F_CREATE_IP']                  = $this->request->getIPAddress();

            $aIdx                                       = $excelFormModel->insertFormDatas( $modelParam );

            if ( $this->db->transStatus() === false || $aIdx === false ) {
                $this->db->transRollback();
                $response['status']                     = 400;
                $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                                   = [];
            $logParam['MB_HISTORY_CONTENT']             = '엑셀양식 추가 - Data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                     = 400;
                $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }
        #------------------------------------------------------------------
        # TODO: 데이터 수정 run
        #------------------------------------------------------------------
        else{

            $modelParam['F_SORT']                       = _elm( $requests, 'i_sort' );
            $modelParam['F_IDX']                        = _elm( $requests, 'i_idx' );
            $modelParam['F_STATUS']                     = _elm( $requests, 'i_status' );
            $modelParam['F_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
            $modelParam['F_UPDATE_IDX']                 = _elm( $this->session->get('_memberInfo') , 'member_idx' );
            $modelParam['F_UPDATE_IP']                  = $this->request->getIPAddress();

            $aData                                      = $excelFormModel->getFormsDataByIdx( $modelParam );

            $aIdx                                       = $excelFormModel->updateFormDatas( $modelParam );

            if ( $this->db->transStatus() === false || $aIdx === false ) {
                $this->db->transRollback();
                $response['status']                     = 400;
                $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                                   = [];
            $logParam['MB_HISTORY_CONTENT']             = '엑셀양식 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ).' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                     = 400;
                $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '저장이 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/excelFormLists' );

        return $this->respond( $response );

    }

    public function deleteForms()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $excelFormModel                             = new ExcelFormModel();
        if( empty( _elm($requests, 'f_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParm                                  = [];
        $modelParam['F_IDX']                        = _elm( $requests, 'f_idx' );
        $aData                                      = $excelFormModel->getFormsDataByIdx( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $excelFormModel->deleteForms( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '엑셀양식 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }



        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제가 완료되었습니다.';


        return $this->respond( $response );

    }

}