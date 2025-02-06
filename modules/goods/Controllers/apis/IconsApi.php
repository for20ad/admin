<?php

namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\goods\Models\IconsModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class IconsApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $aConfig;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->aConfig                              = new goodsConfig();
        $this->aConfig                              = $this->aConfig->goods;
    }

    public function getIconsLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $iconsModel                                 = new IconsModel();
        $requests                                   = _trim($this->request->getPost());


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
        $modelParam['I_NAME']                       = _elm( $requests, 's_icon_name' );
        $modelParam['START_DATE']                   = _elm( $requests, 's_start_date' );
        $modelParam['END_DATE']                     = _elm( $requests, 's_end_date' );
        $modelParam['I_GBN']                        = _elm( $requests, 's_gbn' );
        $modelParam['I_STATUS']                     = _elm( $requests, 's_status' );


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
                $modelParam['order']                = ' I_CREATE_AT DESC';
                break;
            case 'regdate_asc' :
                $modelParam['order']                = ' I_CREATE_AT ASC';
                break;
            case 'update_desc' :
                $modelParam['order']                = ' I_UPDATE_AT DESC';
                break;
            case 'update_asc' :
                $modelParam['order']                = ' I_UPDATE_AT ASC';
                break;
            default :
                $modelParam['order']                = ' I_CREATE_AT DESC';
                break;
        }


        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $aLISTS_RESULT                              = $iconsModel->getIconsLists($modelParam);

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

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['row']                      = $start;
            $view_datas['lists']                    = $list_result;

            $owensView->setViewDatas( $view_datas );


            $page_datas['lists_row']                = view( '\Module\goods\Views\icons\lists_row' , ['owensView' => $owensView] );


            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/goodsIcons' ), '/');
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

    public function iconDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'icon_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $iconsModel                                 = new IconsModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $iconsModel->getIconsDataByIdx( _elm( $requests, 'icon_idx' ) );


        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '폼데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $view_datas['aData']                        = $aData;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\icons\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function iconRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $iconsModel                                 = new IconsModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\icons\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }


    public function iconRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $iconsModel                                 = new IconsModel();

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
                'label'  => '아이콘명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '아이콘 이름을 입력하세요.',
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
        # TODO: 파일 검사
        #------------------------------------------------------------------
        if( empty( $files ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '아이콘 파일을 선택하세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['I_NAME']                       = _elm( $requests, 'i_name' );
        $modelParam['I_CODE']                       = _uniqid( 12, true );
        $modelParam['I_GBN']                        = _elm( $requests, 'i_gbn' );
        $modelParam['I_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['I_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['I_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['I_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'goods/icons',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        foreach( $files as $file ){
            $file_return                            = $this->_upload( $file, $config );

            #------------------------------------------------------------------
            # TODO: 파일처리 실패 시
            #------------------------------------------------------------------
            if( _elm($file_return , 'status') === false ){
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = _elm( $file_return, 'error' );
                return $this->respond( $response, 400 );
            }

            #------------------------------------------------------------------
            # TODO: 데이터모델 세팅
            #------------------------------------------------------------------

            $modelParam['I_IMG_PATH']               = _elm( $file_return, 'uploaded_path');
            $modelParam['I_IMG_NAME']               = _elm( $file_return, 'org_name');
        }

        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_ICONS';
        $_modelParam['data']                        = $modelParam;

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->LogModel->integratInsert( $_modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '상품 아이콘 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '저장 되었습니다.';

        return $this->respond( $response );

    }

    public function iconDetailProc()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $iconsModel                                 = new IconsModel();

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
                'label'  => '아이콘명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '아이콘 이름을 입력하세요.',
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
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['I_IDX']                        = _elm( $requests, 'i_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $iconsModel->getIconsDataByIdx( _elm( $requests, 'i_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        $modelParam['I_NAME']                       = _elm( $requests, 'i_name' );
        $modelParam['I_CODE']                       = _uniqid( 12, true );
        $modelParam['I_GBN']                        = _elm( $requests, 'i_gbn' );
        $modelParam['I_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['I_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['I_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['I_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------

        if( empty( $files ) === false ){
            $config                                 = [
                'path' => 'goods/icons',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( $files as $file ){
                if( $file->getSize() > 0 ){
                    $file_return                    = $this->_upload( $file, $config );

                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }
                    #------------------------------------------------------------------
                    # TODO: 파일 업로드 에러없이 리턴 시 기존 파일 삭제
                    #------------------------------------------------------------------
                    if( file_exists( WRITEPATH. _elm( $aData, 'I_IMG_PATH' ) ) ){
                        unlink( WRITEPATH. _elm( $aData, 'I_IMG_PATH' ) );
                    }
                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------

                    $modelParam['I_IMG_PATH']       = _elm( $file_return, 'uploaded_path');
                    $modelParam['I_IMG_NAME']       = _elm( $file_return, 'org_name');
                }

            }
        }


        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_ICONS';
        $_modelParam['data']                        = $modelParam;
        $_modelParam['where']                       = 'I_IDX';

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->LogModel->integratUpdate( $_modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '상품 아이콘 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';

        return $this->respond( $response );
    }



    public function deleteIcons()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $iconsModel                                 = new IconsModel();
        if( empty( _elm($requests, 'i_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['I_IDX']                        = _elm( $requests, 'i_idx' );
        $aData                                      = $iconsModel->getIconsDataByIdx( _elm( $requests, 'i_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $iconsModel->deleteIcons( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        if( file_exists( WRITEPATH. _elm( $aData, 'I_IMG_PATH' ) ) ){
            unlink( WRITEPATH. _elm( $aData, 'I_IMG_PATH' ) );
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 아이콘 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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

    public function getGoodsIconGroup()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $iconsModel                                 = new IconsModel();

        $_data                                      = $iconsModel->getIconsLists();

        $data                                       = [];


        if( !empty( _elm( $_data, 'lists') ) ){
            foreach( _elm( $_data, 'lists') as $key => $icon ){
                if( _elm( $icon, 'I_GBN' ) == 'L'  ){
                    $data['L'][]                    = $icon;
                }else{
                    $data['P'][]                    = $icon;
                }
            }
        }

        $response                                   = $this->_unset( $response );

        $response['status']                         = 200;
        $response['page_datas']                     = [];
        $response['page_datas']['lists']            = $data;

        return $this->respond( $response );

    }

}