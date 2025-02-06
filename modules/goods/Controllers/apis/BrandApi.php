<?php
#------------------------------------------------------------------
# BrandApi.php
# 브랜드관리 Api
# 김우진
# 2024-08-16 14:59:18
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;


use Module\goods\Models\BrandModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class BrandApi extends ApiController
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

    public function deleteBrandImages()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $brandModel                                 = new BrandModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'f_idx' => [
                'label'  => 'f_idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '파일인덱스 누락.',
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

        $aData                                      = $brandModel->getBrandFileDataByIdx( _elm( $requests, 'f_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '파일 데이터가 없습니다.';

            return $this->respond( $response );
        }
        $this->db->transBegin();

        $aStatus                                    = $brandModel->deleteBrandFileDataByIdx( _elm( $requests, 'f_idx' ) );
        if ( $this->db->transStatus() === false || $aStatus == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 데이터 삭제
        #------------------------------------------------------------------
        $finalFilePath                  = WRITEPATH . _elm( $aData, 'F_PATH' );
        if (file_exists($finalFilePath)) {
            @unlink($finalFilePath);
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                              = [];
        $logParam['MB_HISTORY_CONTENT']        = '상품 브랜드 추가 파일삭제 - data:'.json_encode( $requests, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                    = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                = 400;
            $response['alert']                 = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );

    }

    public function getBrandLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];


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

        $modelParam['order']                        = ' C_SORT ASC';

        ###########################################################
        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        $brnad                                      = [];

        #############################################################


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['_parent_idx']              = _elm( $requests, 'parent_idx' );

            #------------------------------------------------------------------
            # TODO: 메뉴 트리 적용
            #------------------------------------------------------------------
            $brand                                  = [];

            if( empty( $brand_lists ) === false  ){
                #------------------------------------------------------------------
                # TODO: 트리형식으로 리스트 변경
                #------------------------------------------------------------------

                $view_datas['brand_tree_lists']     = _build_tree( $brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

                foreach (_elm($view_datas, 'brand_tree_lists', []) as $kIDX => $vBRAND)
                {
                    $brand[_elm($vBRAND, 'C_IDX')]  = _elm($vBRAND, 'C_BRAND_NAME');

                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD)
                        {
                            $brand[_elm($vBRAND_CHILD, 'C_IDX')] = '   >>>' ._elm($vBRAND_CHILD, 'C_BRAND_NAME');
                        }
                    }
                }
            }



            $view_datas['lists']                    = $brand;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\brand\lists_row' , ['owensView' => $owensView] );


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

    public function brandDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'brand_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $brandModel                              = new BrandModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $_parentInfo                                = $brandModel->getParentInfo( _elm( $requests, 'parentIdx' )  );

        if( empty( $_parentInfo ) ){
            $_parentInfo['C_PARENT_IDX']            = 0;
            $_parentInfo['C_BRAND_NAME']            = '최상위';
        }
        $aData                                      = $brandModel->getBrandDataByIdx( _elm( $requests, 'brand_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '브랜드 데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $aData['files']                             = $brandModel->getBrandFiles( _elm( $requests, 'brand_idx' ) );


        $view_datas['aData']                        = $aData;
        $view_datas['parentInfo']                   = $_parentInfo;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\brand\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function brandRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $brandModel                              = new BrandModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;
        $_brandCode                                 = $brandModel->getBrandCode( _elm( $requests, 'parentIdx' ) );
        $_parentInfo                                = $brandModel->getParentInfo( _elm( $requests, 'parentIdx' )  );

        $_max                                       = (int)_elm($_brandCode, 'max') ?? 0;
        //echo $_max;
        $max                                        = (int)$_max + 1;


        if( empty( $_parentInfo ) ){
            $_parentInfo['C_PARENT_IDX']            = 0;
            $_parentInfo['C_BRAND_NAME']            = '최상위';

            if ( _elm($_brandCode, 'max' ) >= 999) {
                $newCode                            = str_pad( ( $max ), 3, '0', STR_PAD_LEFT);
            } else {
                $newCode                            = str_pad( ( $max ), 3, '0', STR_PAD_LEFT);
            }
        } else {
            // 부모 코드가 있는 경우에도 같은 방식으로 처리
            $newCode                                = _elm( $_parentInfo, 'C_PARENT_CODE' ).str_pad( ( $max ), 3, '0', STR_PAD_LEFT);
        }



        $view_datas['brandCode']                    = $newCode;
        $view_datas['parentInfo']                   = $_parentInfo;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\brand\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }
    public function updateBrandOrder(){
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $brandModel                                 = new BrandModel();

        if( empty( _elm( $requests, 'order' ) ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 정렬순서 업데이트
        #------------------------------------------------------------------

        $this->db->transBegin();

        foreach( _elm( $requests, 'order' ) as $sort => $c_idx ){
            $modelParam                             = [];
            $modelParam['C_SORT']                   = $sort + 1;
            $modelParam['C_IDX']                    = $c_idx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aStatus                                = $brandModel->setBrandSort( $modelParam );

            if ( $this->db->transStatus() === false || $aStatus === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '정렬 순서 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                              = [];
        $logParam['MB_HISTORY_CONTENT']        = '상품 브랜드 정렬순서 변경 - data:'.json_encode( _elm($requests, 'order'), JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                    = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                = 400;
            $response['alert']                 = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------



        $this->db->transCommit();
        $response                              = $this->_unset($response);
        $response['status']                    = 200;

        return $this->respond($response);
    }

    public function brandRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $brandModel                                 = new BrandModel();


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_brand_name' => [
                'label'  => '브랜드명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '브랜드 이름을 입력하세요.',
                ],
            ],
            'i_brand_name_eng' => [
                'label'  => '브랜드 영문명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '브랜드영문 이름을 입력하세요.',
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
        $_sort                                      = $brandModel->getSortMax( _elm( $requests, 'i_parent_idx' ) );

        $modelParam                                 = [];
        $modelParam['C_PARENT_IDX']                 = _elm( $requests, 'i_parent_idx' );
        $modelParam['C_BRAND_NAME']                 = _elm( $requests, 'i_brand_name' );
        $modelParam['C_BRAND_NAME_ENG']             = _elm( $requests, 'i_brand_name_eng' );
        $modelParam['C_BRAND_CODE']                 = _elm( $requests, 'i_brand_code' );
        $modelParam['C_ORDERING_CD']                = _elm( $requests, 'i_ordering_cd' );
        $modelParam['C_SORT']                       = _elm( $_sort, 'max' ) + 1;

        $modelParam['C_META_TITLE']                 = _elm( $requests, 'i_meta_title' );
        $modelParam['C_META_AUTHOR']                = _elm( $requests, 'i_meta_author' );
        $modelParam['C_META_DESCRIPTION']           = _elm( $requests, 'i_meta_description' );
        $modelParam['C_META_KEYWORD']               = _elm( $requests, 'i_meta_keyword' );

        $modelParam['C_STATUS_PC']                  = _elm( $requests, 'i_status_pc' );
        $modelParam['C_STATUS_MOBILE']              = _elm( $requests, 'i_status_mobile' );
        $modelParam['C_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'goods/brand',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        foreach( $files as $key => $file ){
            if( $key == 'i_brand_mobile_img'){
                if( $file->getSize() > 0 ){
                    $file_return                        = $this->_upload( $file, $config );
                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']             = 400;
                        $response['alert']              = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------
                    // 파일 키에 따라 모델 파라미터에 저장

                    $modelParam['C_BRAND_PC_IMG']   = _elm($file_return, 'uploaded_path');
                    $modelParam['C_BRAND_MOBILE_IMG'] = _elm($file_return, 'uploaded_path');

                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_BRAND';
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
        # TODO: 배너이미지 처리
        #------------------------------------------------------------------
        foreach( $files as $key => $file ){
            if( $key != 'i_brand_mobile_img' ){
                if (is_array($file)) {
                    foreach ($file as $mKey => $multiFile) {
                        if ($multiFile->getSize() > 0) {
                            $file_return = $this->_upload($multiFile, $config);

                            // 파일 처리 실패 시
                            if (_elm($file_return, 'status') === false) {
                                $this->db->transRollback();
                                $response['status'] = 400;
                                $response['alert']  = _elm($file_return, 'error');
                                return $this->respond($response, 400);
                            }

                            // 파일 처리 성공 시 fileParam에 저장
                            $fileParam = [
                                'F_B_IDX'           => $aStatus, // 브랜드 ID
                                'F_NAME'            => $multiFile->getClientName(),
                                'F_PATH'            => _elm($file_return, 'uploaded_path'),
                                'F_VIEW_TYPE'       => _elm( _elm( $requests, 'device_type' ), $mKey),
                                'F_EXT'             => $multiFile->getClientExtension(),
                                'F_SIZE'            => $multiFile->getSize(),
                                'F_TYPE'            => $multiFile->getClientMimeType(),
                                'F_CREATE_AT'       => date('Y-m-d H:i:s'),
                                'F_CREATE_IP'       => $this->request->getIPAddress(),
                                'F_CREATE_MB_IDX'   => _elm( $this->session->get('_memberInfo') , 'member_idx' ), // 로그인한 사용자 ID
                            ];

                            #------------------------------------------------------------------
                            # TODO: 파일 입력
                            #------------------------------------------------------------------
                            $f_idx                  = $brandModel->insertBrandFiles( $fileParam );
                            if ( $this->db->transStatus() === false || $f_idx === false ) {
                                $this->db->transRollback();
                                $response['status']                     = 400;
                                $response['alert']                      = '추가 파일 처리중 오류발생.. 다시 시도해주세요.';
                                return $this->respond( $response );
                            }

                        }

                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 브랜드 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['reload']                         = true;

        return $this->respond( $response );

    }

    public function brandDetailProc()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $brandModel                                 = new BrandModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_parent_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX가 없습니다. 새로고침 후 시도해주세요.',
                ],
            ],
            'i_brand_name' => [
                'label'  => '브랜드명',
                'rules'  => 'trim|required|regex_match[/^[a-zA-Z0-9가-힣 ]+$/]',
                'errors' => [
                    'required' => '브랜드 이름을 입력하세요.',
                    'regex_match' => '브랜드 명은 특수문자를 허용하지 않습니다.',
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
        $modelParam['C_IDX']                        = _elm( $requests, 'i_brand_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $brandModel->getBrandDataByIdx( _elm( $requests, 'i_brand_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }



        $modelParam['C_PARENT_IDX']                 = _elm( $requests, 'i_parent_idx' );
        $modelParam['C_BRAND_NAME']                 = _elm( $requests, 'i_brand_name' );
        $modelParam['C_BRAND_NAME_ENG']             = _elm( $requests, 'i_brand_name_eng' );
        $modelParam['C_BRAND_CODE']                 = _elm( $requests, 'i_brand_code' );
        $modelParam['C_ORDERING_CD']                = _elm( $requests, 'i_ordering_cd' );
        $modelParam['C_SORT']                       = _elm( $aData, 'C_SORT' );

        $modelParam['C_META_TITLE']                 = _elm( $requests, 'i_meta_title' );
        $modelParam['C_META_AUTHOR']                = _elm( $requests, 'i_meta_author' );
        $modelParam['C_META_DESCRIPTION']           = _elm( $requests, 'i_meta_description' );
        $modelParam['C_META_KEYWORD']               = _elm( $requests, 'i_meta_keyword' );

        $modelParam['C_STATUS_PC']                  = _elm( $requests, 'i_status_pc' );
        $modelParam['C_STATUS_MOBILE']              = _elm( $requests, 'i_status_mobile' );
        $modelParam['C_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );


        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'goods/brand',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        foreach( $files as $key => $file ){
            if( $key == 'i_brand_mobile_img' ){
                if( $file->getSize() > 0 ){
                    $file_return                        = $this->_upload( $file, $config );
                    #------------------------------------------------------------------
                    # TODO: 파일처리 실패 시
                    #------------------------------------------------------------------
                    if( _elm($file_return , 'status') === false ){
                        $this->db->transRollback();
                        $response['status']             = 400;
                        $response['alert']              = _elm( $file_return, 'error' );
                        return $this->respond( $response, 400 );
                    }

                    #------------------------------------------------------------------
                    # TODO: 데이터모델 세팅
                    #------------------------------------------------------------------
                    // 파일 키에 따라 모델 파라미터에 저장

                    $modelParam['C_BRAND_PC_IMG'] = _elm($file_return, 'uploaded_path');
                    if( empty( _elm( $aData, 'C_BRAND_PC_IMG' ) ) === false ){
                        if( file_exists( WRITEPATH._elm( $aData, 'C_BRAND_PC_IMG' ) ) ){
                            unlink( WRITEPATH._elm( $aData, 'C_BRAND_PC_IMG' ) );
                        }
                    }
                    $modelParam['C_BRAND_MOBILE_IMG'] = _elm($file_return, 'uploaded_path');


                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_BRAND';
        $_modelParam['data']                        = $modelParam;
        $_modelParam['where']                       = 'C_IDX';

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->LogModel->integratUpdate( $_modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '업데이트 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        if( empty( _elm( $requests, 'device_type') ) ){
            foreach ( $_elm( $requests, 'device_type') as $fileIdx => $deviceType) {
                $aParam                             = [];
                $aParam['F_IDX']                    = $fileIdx;
                $aParam['F_VIEW_TYPE']              = $deviceType;
                $aParam['F_UPDATE_AT']              = date( 'Y-m-d H:i:s' );
                $aParam['F_UPDATE_IP']              = $this->request->getIPAddress();
                $aParam['F_UPDATE_MB_IDX']          = _elm( $this->session->get('_memberInfo') , 'member_idx' );


                $dStatus                            = $brandModel->updateBrandFileData( $aParam );
                if ( $this->db->transStatus() === false || $aStatus === false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '파일 타입 업데이트 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 배너이미지 처리
        #------------------------------------------------------------------
        foreach( $files as $key => $file ){
            if( $key != 'i_brand_mobile_img'){
                if (is_array($file)) {
                    foreach ($file as $mKey => $multiFile) {
                        if ($multiFile->getSize() > 0) {
                            $file_return = $this->_upload($multiFile, $config);

                            // 파일 처리 실패 시
                            if (_elm($file_return, 'status') === false) {
                                $this->db->transRollback();
                                $response['status'] = 400;
                                $response['alert']  = _elm($file_return, 'error');
                                return $this->respond($response, 400);
                            }

                            // 파일 처리 성공 시 fileParam에 저장
                            $fileParam = [
                                'F_B_IDX'           => _elm( $requests, 'i_brand_idx' ), // 브랜드 ID
                                'F_NAME'            => $multiFile->getClientName(),
                                'F_PATH'            => _elm($file_return, 'uploaded_path'),
                                'F_VIEW_TYPE'       => _elm( _elm( $requests, 'device_type' ), $mKey),
                                'F_EXT'             => $multiFile->getClientExtension(),
                                'F_SIZE'            => $multiFile->getSize(),
                                'F_TYPE'            => $multiFile->getClientMimeType(),
                                'F_CREATE_AT'       => date('Y-m-d H:i:s'),
                                'F_CREATE_IP'       => $this->request->getIPAddress(),
                                'F_CREATE_MB_IDX'   => _elm( $this->session->get('_memberInfo') , 'member_idx' ), // 로그인한 사용자 ID
                            ];

                            #------------------------------------------------------------------
                            # TODO: 파일 입력
                            #------------------------------------------------------------------
                            $f_idx                  = $brandModel->insertBrandFiles( $fileParam );
                            if ( $this->db->transStatus() === false || $f_idx === false ) {
                                $this->db->transRollback();
                                $response['status']                     = 400;
                                $response['alert']                      = '추가 파일 처리중 오류발생.. 다시 시도해주세요.';
                                return $this->respond( $response );
                            }

                        }
                    }
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 브랜드 수정 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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



    public function deleteBrand()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $brandModel                                 = new BrandModel();

        if( empty( _elm($requests, 'brand_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['C_IDX']                        = _elm( $requests, 'brand_idx' );
        $aData                                      = $brandModel->getBrandDataByIdx( _elm( $requests, 'brand_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->deleteBrandAndChildren($requests['brand_idx']);
        if ( $this->db->transStatus() === false || _elm( $aStatus, 'status' ) !== 200 ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = _elm( $aStatus, 'messages' );
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 현재 데이터의 파일 모두 삭제
        #------------------------------------------------------------------
        if( empty( _elm( $aData, 'C_BRAND_PC_IMG' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'C_BRAND_PC_IMG' ) ) ){
                unlink( WRITEPATH._elm( $aData, 'C_BRAND_PC_IMG' ) );
            }
        }
        if( empty( _elm( $aData, 'C_BRAND_MOBILE_IMG' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'C_BRAND_MOBILE_IMG' ) ) ){
                unlink( WRITEPATH._elm( $aData, 'C_BRAND_MOBILE_IMG' ) );
            }
        }
        if( empty( _elm( $aData, 'C_BRAND_PC_BANNER' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'C_BRAND_PC_BANNER' ) ) ){
                unlink( WRITEPATH._elm( $aData, 'C_BRAND_PC_BANNER' ) );
            }
        }
        if( empty( _elm( $aData, 'C_BRAND_MOBILE_BANNER' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'C_BRAND_MOBILE_BANNER' ) ) ){
                unlink( WRITEPATH._elm( $aData, 'C_BRAND_MOBILE_BANNER' ) );
            }
        }

        #------------------------------------------------------------------
        # TODO: 추가 파일삭제
        #------------------------------------------------------------------
        $aFiles                                     = $brandModel->getBrandFilesByParentIdx(  _elm( $requests, 'brand_idx' ) );
        if( empty( $aFiles ) == false ){
            foreach( $aFiles as $aKey => $file ){
                $aDelStatus                         = $brandModel->deleteBrandFileDataByIdx( _elm( $file, 'F_IDX' ) );
                if ( $this->db->transStatus() === false || $aStatus == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '삭제 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
                $finalFilePath                       = WRITEPATH . _elm( $file, 'F_PATH' );
                if (file_exists($finalFilePath)) {
                    @unlink($finalFilePath);
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 브랜드 삭제 - orgData:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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
        $response                                   = $this->_unset( $response );
        $response['status']                         = 200;

        $response['alert']                          = '삭제가 완료되었습니다.';


        return $this->respond( $response );

    }

    private function deleteBrandAndChildren($brandIdx)
    {
        $brandModel                                 = new BrandModel();

        #------------------------------------------------------------------
        # TODO: 하위메뉴 가져옴
        #------------------------------------------------------------------
        $childBrands                                = $brandModel->getChildBrand($brandIdx);

        // 만약 getChildMenus가 실패하면 빈 배열로 처리
        if ($childBrands === false) {
            $childBrands                            = [];
        }

        #------------------------------------------------------------------
        # TODO: 하위 메뉴가 있으면 재귀적으로 삭제
        #------------------------------------------------------------------
        foreach ($childBrands as $childBrand) {
            $loopResult                            = $this->deleteBrandAndChildren($childBrand['C_IDX']);

            #------------------------------------------------------------------
            # TODO: 파일삭제
            #------------------------------------------------------------------
            if( empty( _elm( $childBrand, 'C_BRAND_PC_IMG' ) ) === false ){
                if( file_exists( WRITEPATH._elm( $childBrand, 'C_BRAND_PC_IMG' ) ) ){
                    unlink( WRITEPATH._elm( $childBrand, 'C_BRAND_PC_IMG' ) );
                }
            }
            if( empty( _elm( $childBrand, 'C_BRAND_MOBILE_IMG' ) ) === false ){
                if( file_exists( WRITEPATH._elm( $childBrand, 'C_BRAND_MOBILE_IMG' ) ) ){
                    unlink( WRITEPATH._elm( $childBrand, 'C_BRAND_MOBILE_IMG' ) );
                }
            }
            if( empty( _elm( $childBrand, 'C_BRAND_PC_BANNER' ) ) === false ){
                if( file_exists( WRITEPATH._elm( $childBrand, 'C_BRAND_PC_BANNER' ) ) ){
                    unlink( WRITEPATH._elm( $childBrand, 'C_BRAND_PC_BANNER' ) );
                }
            }
            if( empty( _elm( $childBrand, 'C_BRAND_MOBILE_BANNER' ) ) === false ){
                if( file_exists( WRITEPATH._elm( $childBrand, 'C_BRAND_MOBILE_BANNER' ) ) ){
                    unlink( WRITEPATH._elm( $childBrand, 'C_BRAND_MOBILE_BANNER' ) );
                }
            }

            if ($loopResult['status'] === 400) {
                return $loopResult;
            }
        }

        #------------------------------------------------------------------
        # TODO: 현재 메뉴 삭제
        #------------------------------------------------------------------
        if (!$brandModel->deleteBrand($brandIdx)) {
            return [
                'status' => 400,
                'error' => 400,
                'messages' => '현재 브랜드 삭제 중 오류 발생'
            ];
        } else {
            return [
                'status' => 200,
                'messages' => 'success'
            ];
        }
    }

    public function getBrandDropDown()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $brandModel                                 = new BrandModel();

        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;
        $modleParam                                 = [];
        $modelParam['order']                        = ' C_SORT ASC';

        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( $aLISTS_RESULT, 'lists' );
        if( empty( $brand_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $view_datas['brand_tree_lists']          = _build_tree( $brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

            foreach (_elm($view_datas, 'brand_tree_lists', []) as $kIDX => $vBRAND)
            {
                $brand[_elm($vBRAND, 'C_IDX')]       = _elm($vBRAND, 'C_BRAND_NAME');

                if (empty($vCODE['CHILD']) === false)
                {
                    foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD)
                    {
                        $brand[_elm($vBRAND_CHILD, 'C_IDX')] = '   >>>' ._elm($vBRAND_CHILD, 'C_BRAND_NAME');
                    }
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $view_datas['aData']                        = $brand;

        $owensView->setViewDatas( $view_datas );

        if( empty( _elm( $requests, 'viewPage' ) ) === false ){
            $page_datas['detail']                   = view( '\Module\promotion\Views\coupon\_brand_dropdown' , ['owensView' => $owensView] );
        }else{
            $page_datas['detail']                   = view( '\Module\goods\Views\goods\_brand_dropdown' , ['owensView' => $owensView] );
        }


        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }

    public function getPopBrandManage( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $modelParam                                 = [];
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



        $modelParam['order']                        = ' C_SORT ASC';

        ###########################################################
        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        $brand                                      = [];

        #############################################################


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['_parent_idx']              = _elm( $requests, 'parent_idx' );

            #------------------------------------------------------------------
            # TODO: 메뉴 트리 적용
            #------------------------------------------------------------------

            if( empty( $brand_lists ) === false  ){
                #------------------------------------------------------------------
                # TODO: 트리형식으로 리스트 변경
                #------------------------------------------------------------------

                $view_datas['brand_tree_lists']     = _build_tree( $brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

                foreach (_elm($view_datas, 'brand_tree_lists', []) as $kIDX => $vBRAND)
                {
                    $brand[_elm($vBRAND, 'C_IDX')]  = _elm($vBRAND, 'C_BRAND_NAME');

                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD)
                        {
                            $brand[_elm($vBRAND_CHILD, 'C_IDX')] = '   >>>' ._elm($vBRAND_CHILD, 'C_BRNAD_NAME');
                        }
                    }
                }
            }



            $view_datas['lists']                    = $brand;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\brand\pop_list_row' , ['owensView' => $owensView] );


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

    public function getPopBrandLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
        $requests                                   = _trim($this->request->getPost());


        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 10;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 10;
        }



        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $modelParam['notIdx']                       = explode( ',', _elm( $requests, 'picLists' ) );

        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );
        //print_r( $aLISTS_RESULT );
        // if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){
        //     foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $aLists ){

        //     }
        // }

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

        $search_count                               = _elm( $aLISTS_RESULT, 'search_count', 0 );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        #############################################################
        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['search_count']             = $search_count;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;



            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/hotBrand' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;


            $view_datas['pagination']               = $this->_pagination($paging_param);
            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\bundle\hotBrand\pop_lists' , ['owensView' => $owensView] );

        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }


    public function getPopBrandListsRow( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
        $requests                                   = _trim($this->request->getPost());


        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 10;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 10;
        }



        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;

        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $modelParam['notIdx']                       = explode( ',', _elm( $requests, 'picLists' ) );

        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );
        //print_r( $aLISTS_RESULT );
        // if( empty( _elm( $aLISTS_RESULT, 'lists' ) ) === false ){
        //     foreach( _elm( $aLISTS_RESULT, 'lists' ) as $aKey => $aLists ){

        //     }
        // }

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

        $search_count                               = _elm( $aLISTS_RESULT, 'search_count', 0 );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        #############################################################
        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['search_count']             = $search_count;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/hotBrand' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;


            $page_datas['pagination']               = $this->_pagination($paging_param);

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\bundle\hotBrand\pop_lists_row' , ['owensView' => $owensView] );


        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function brandAddRows()
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $requests, 'brandIdxs' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '선택된 브랜드가 없습니다.';

            return $this->respond( $response );
        }
        $idxs                                       = explode(',', _elm( $requests, 'brandIdxs' )  );
        $aLISTS_RESULT                              = $brandModel->getBrandDataByIdxs( $idxs );
        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );
            $view_datas['aColorConfig']             = $this->sharedConfig::$goodsColor;

            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );

            $page_datas['lists_row']                = view( '\Module\goods\Views\brand\_addBrandRows' , ['owensView' => $owensView] );

        }

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;


        return $this->respond($response);
    }

}