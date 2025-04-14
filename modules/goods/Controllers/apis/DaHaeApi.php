<?php
#------------------------------------------------------------------
# DaHaeApi.php
# 다해 API
# 김우진
# 2025-02-10 13:40:48
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\dahae\Config\Config as DahaeConfig;
use Module\goods\Models\DahaeModel;

use Module\goods\Controllers\GoodsInfoDAO;
use Module\goods\Controllers\GoodsOptionsDAO;

use Module\goods\Models\BrandModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\GoodsModel;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use CodeIgniter\HTTP\Files\UploadedFile;

use Exception;

use App\Libraries\OwensView;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;


use League\HTMLToMarkdown\HtmlConverter;

class DaHaeApi extends ApiController
{
    protected $dahaeConfig;
    protected $dModel, $brandModel, $cateModel, $goodsModel;
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->dahaeConfig                          = new DahaeConfig();
        $this->dModel                               = new DahaeModel();
        $this->brandModel                           = new BrandModel();
        $this->cateModel                            = new CategoryModel();
        $this->goodsModel                           = new GoodsModel();
        $this->db = \Config\Database::connect();
    }

    public function getAuthToken()
    {
        $request                                    = '';
        $aConfig                                    = $this->dahaeConfig->dahae;
        $apiKey                                     = _elm( $aConfig, 'apiRealKey' );
        $url                                        = _elm( _elm( $aConfig, 'apiUrl' ), 'token' );
        #------------------------------------------------------------------
        # TODO: GET
        #------------------------------------------------------------------
        $response                                   = $this->_curl(  $url, 'GET','' , ['apiKey' => $apiKey ] );
        #------------------------------------------------------------------
        # TODO: PUT
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'PUT', ['Content-Type: application/json'], $jwtToken, ['name' => 'updatedItem', 'price' => 150]);

        #------------------------------------------------------------------
        # TODO: DELETE
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'DELETE', $jwtToken );

        #------------------------------------------------------------------
        # TODO: PATCH
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'PATCH', $jwtToken, ['price' => 120]);
        return _elm($response , 'process');

    }

    public function getProductDetail( $token, $modelCode )
    {
        ini_set('memory_limit', '256M'); // 256MB로 설정

        $aConfig                                    = $this->dahaeConfig->dahae;

        $optionUrl                                  = _elm( _elm( $aConfig, 'apiUrl' ), 'modelDetail' );

        $param                                      = [
            'modelCode'                                  => $modelCode,
        ];

        $response                                   = $this->_curl( $optionUrl, 'GET', $token, $param );
        $data                                       = json_decode( _elm( $response, 'process' ), true );

        return $data;
    }

    private function _curl($_urlParams, $method = 'GET', $jwtToken = '', $data = [])
    {
        $curl = curl_init();

        // 기본 헤더 설정 (JWT 토큰 포함)
        $headers = [
            'Content-Type: application/json'
        ];
        if (!empty($jwtToken)) {
            $headers[] = 'Authorization: Bearer ' . $jwtToken;  // JWT 토큰 추가
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
         // GET 요청일 경우 URL에 쿼리 스트링 추가
        if (strtoupper($method) === 'GET' && !empty($data)) {
            $_urlParams .= '?' . http_build_query($data);
        }

        // URL 설정
        // echo $_urlParams;

        curl_setopt($curl, CURLOPT_URL, $_urlParams);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // HTTP 메소드에 따라 cURL 옵션 설정
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // POST 데이터 설정
                }
                break;

            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // PUT 데이터 설정
                }
                break;

            case 'PATCH':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // PATCH 데이터 설정
                }
                break;

            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // DELETE 데이터 설정
                }
                break;

            default: // GET 요청의 경우
                curl_setopt($curl, CURLOPT_HTTPGET, true);

                break;
        }

        // 응답 실행
        $response = curl_exec($curl);

        // cURL 오류 체크
        if (curl_errno($curl)) {
            echo 'cURL Error: ' . curl_error($curl);
        }

        curl_close($curl);

        // 응답을 JSON으로 디코딩
        $data = json_decode($response, true);

        return $data;
    }


    public function getDaHaeGoodsHeader()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $owensView                                  = new OwensView();
        ini_set('memory_limit', '-1'); // 무제한 설정
        set_time_limit(0);
        $modelParam                                 = [];
        $modelParam['modelCode']                    = _elm( $requests, 's_keyword', '' );
        $modelParam['size']                         = empty( _elm( $requests, 'per_page' ) ) ?  20 : _elm( $requests, 'per_page' );
        $modelParam['page']                         = empty( _elm( $requests, 'page' ) ) ?  1 : _elm( $requests, 'page' );
        $modelParam['regDate']                      = _elm( $requests, 's_start_date' );
        $modelParam['endDate']                      = _elm( $requests, 's_end_date' );
        $start                                      = _elm($modelParam, 'size' ) * ( _elm( $modelParam, 'page' ) -1 );

        $jwtToken                                   = $this->getAuthToken();
        $page                                       = empty($page) ? 1:$page;

        $aConfig                                    = $this->dahaeConfig->dahae;
        $url                                        = _elm( _elm( $aConfig, 'apiUrl' ), 'modelHeader' );
        $optionUrl                                  = _elm( _elm( $aConfig, 'apiUrl' ), 'modelDetail' );

        $converter                                  = new HtmlConverter();
        #------------------------------------------------------------------
        # TODO: POST
        #------------------------------------------------------------------
        $apiResponse                                = $this->_curl( $url, 'GET', $jwtToken, $modelParam);

        $data                                       = json_decode( _elm( $apiResponse, 'process' ), true );

        $totalCount = _elm( $apiResponse, 'totalCount' );
        $view_datas                                 = [];
        $modelParam['i_is_not_bridge']              = _elm( $requests, 'i_is_not_bridge' );

        if( empty( $data ) === false ){
            foreach( $data as $iKey => &$iData ){
                $mallDataChk                        = $this->dModel->goodsDataByDahaeCode( _elm( $iData, 'ModelCode' ) );
                $iData['G_IDX']                     = _elm( _elm( $mallDataChk, 0 ), 'G_IDX' );
                if( _elm( $modelParam, 'i_is_not_bridge' ) == 'Y' ){
                    if( empty( _elm( $iData, 'G_IDX' ) ) === false ){
                        unset( $data[$iKey] );
                    }
                }
            }
            unset( $iData );
        }
        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];
            $view_datas['row']                      = $start;
            $view_datas['aConfig']                  = $aConfig;
            $view_datas['total_rows']               = $totalCount;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );

            $view_datas['lists']                    = $data;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\dahae\lists_row' , ['owensView' => $owensView] );

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = _elm( $modelParam, 'size' );
            $paging_param['total_rows']             = $totalCount;
            $paging_param['base_url']               = rtrim( _link_url( '/goods/bridgeLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = _elm( $modelParam, 'page' );

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }
        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------



        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $totalCount;

        return $this->respond($response);
    }

    public function registerDahaeProduct()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();


        if( empty( _elm( $requests, 'i_dahae_code' ) ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '등록할 싱픔데이터를 선택해주세요.';

            return $this->respond( $response );
        }

        foreach( _elm( $requests, 'i_dahae_code' ) as $aKey => $modelCode ){
            $modelParam                             = [];
            $modelParam['modelCode']                = $modelCode;

            $jwtToken                               = $this->getAuthToken();
            $page                                   = empty($page) ? 1:$page;

            $aConfig                                = $this->dahaeConfig->dahae;
            $url                                    = _elm( _elm( $aConfig, 'apiUrl' ), 'modelHeader' );
            $optionUrl                              = _elm( _elm( $aConfig, 'apiUrl' ), 'modelDetail' );

            $converter                              = new HtmlConverter();
            #------------------------------------------------------------------
            # TODO: POST
            #------------------------------------------------------------------
            $apiResponse                            = $this->_curl( $url, 'GET', $jwtToken, $modelParam);

            $data                                   = json_decode( _elm( $apiResponse, 'process' ), true );

            if( empty( $data ) === false ){
                #------------------------------------------------------------------
                # TODO: 상품 DAO
                #------------------------------------------------------------------
                $gInfoDao                           = new GoodsInfoDAO();
                #------------------------------------------------------------------
                # TODO: 상품 옵션 DAO
                #------------------------------------------------------------------
                $gOptionDao                         = new GoodsOptionsDAO();
                foreach( $data as $key => $dahaeProduct ){
                    $optData                                        = $this->getProductDetail( $jwtToken, _elm( $dahaeProduct, 'ModelCode' ) );
                    $data[$key]['optInfo']                          = $optData;
                    print_R( $data );
                    die;
                    #------------------------------------------------------------------
                    # TODO: 나중에 한번에 넣을거임
                    #------------------------------------------------------------------
                    // $uniqueGPRID = $this->generateUniqueGPRID(16, true);
                    // $gInfoDao->set( 'G_PRID',                       $uniqueGPRID );                               //'상품코드'
                    $gInfoDao->set( 'G_GODO_GOODSNO',               _elm( $dahaeProduct, 'GodomallCode' ) );         //'고도몰 상품IDX',
                    $gInfoDao->set( 'G_DAHAE_P_CODE',               _elm( $dahaeProduct, 'ModelCode' ) );            //'다해소프트 상품 코드',
                    $gInfoDao->set( 'G_NAME',                       _elm( $dahaeProduct, 'ModelName' ) );            //'상품명',

                    $gInfoDao->set( 'G_NAME_ENG',                   _elm( $dahaeProduct, 'MainProductName' ) );      //'상품명 영문',

                    $gInfoDao->set( 'G_SHORT_DESCRIPTION',          $converter->convert( _elm( $dahaeProduct, 'Description', null, true )?? ' ' ) );            //'상품 요약설명',

                    #------------------------------------------------------------------
                    # TODO: 상품 상세페이지는 몰에서 직접 등록
                    #------------------------------------------------------------------
                    // $gInfoDao->set( 'G_CONTETN_IS_SAME_FLAG',       strtoupper( _elm( $esGoodsInfo, 'goodsDescriptionSameFl' ) ) );                    //'PC와 내용 동일 체크값',
                    // $gInfoDao->set( 'G_CONTENT_PC',
                    //     str_replace(
                    //         'https://lundhags.speedgabia.com/',
                    //         'https://admin.brav.co.kr/upload/gabia/',
                    //         $converter->convert( _elm( $esGoodsInfo, 'goodsDescription', null, true )?? ' ' )
                    //     )
                    // );            //'상품상세 설명 PC',
                    // if( _elm( $esGoodsInfo, 'goodsDescriptionSameFl' )  == 'y' ){
                    //     $gInfoDao->set( 'G_CONTENT_MOBILE',
                    //         str_replace(
                    //             'https://lundhags.speedgabia.com/',
                    //             'https://admin.brav.co.kr/upload/gabia/',
                    //             $converter->convert( _elm( $esGoodsInfo, 'goodsDescription', null, true )?? ' ' )
                    //         )
                    //     );            //'상품상세 설명 MOBILE',
                    // }else{
                    //     $gInfoDao->set( 'G_CONTENT_MOBILE',
                    //         str_replace(
                    //             'https://lundhags.speedgabia.com/',
                    //             'https://admin.brav.co.kr/upload/gabia/',
                    //             $converter->convert( _elm( $esGoodsInfo, 'goodsDescriptionMobile', null, true )?? ' ' )
                    //         )
                    //     );      //'상품상세 설명 MOBILE',
                    // }

                    $gInfoDao->set( 'G_SEARCH_KEYWORD',             _elm( $dahaeProduct, 'SearchKeywords', null, true ) ); //'검색키워드 ,로 분리',
                    $gInfoDao->set( 'G_ADD_POINT',                  0);

                }
            }
        }
    }

    public function getDahaeOptionCode(){
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $goodsModel                                 = new GoodsModel();
        $owensView                                  = new OwensView();
        $modelParam                                 = [];

        $modelParam['order']                        = ' idx DESC';
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

        if( empty( _elm( $requests, 'g_name' ) ) === false ){
            $modelParam['d_g_name']                 = _elm( $requests, 'g_name' );
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;



        ###########################################################
        $aLISTS_RESULT                              = $this->goodsModel->getDahaeOptionCodeLists( $modelParam );



        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );
        if (!empty($lists)) {
            foreach ($lists as $lKey => $data) {
                // 'cateCd' 값을 3자리씩 분리
                $result = []; // 결과를 담을 배열
                $tempString = ''; // 단계적으로 값을 연결할 변수

                $result[] = '<a href="javascript:$(\'#frm_search [name=g_name]\').val(\'' . _elm($data, 'd_option_g_name') . '\');getSearchList(1)">' . _elm($data, 'd_option_g_name') . '</a>';
                $result[] = '<a href="javascript:$(\'#frm_search [name=g_name]\').val(\'' . _elm($data, 'd_option_name') . '\');getSearchList(1)">' . _elm($data, 'd_option_name') . '</a>';
                $lists[$lKey]['codes']         = implode(' < ',$result);
            }
        }



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
            $view_datas['row']                      = $start;
            $view_datas['total_rows']               = $total_count;

            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\dahae\option_lists_row' , ['owensView' => $owensView] );

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/design/banner' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);


        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $aLISTS_RESULT['pagination']            = _elm($page_datas, 'pagination' );
            return $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);

    }

    public function updateDahaeOptionCode()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $categoryModel                              = new CategoryModel();
        $goodsModel                                 = new GoodsModel();

        $aData                                      = $this->goodsModel->getDahaeOptionCodeByIdx( _elm( $requests, 'idx' ) );

        $modelParam                                 = [];
        $modelParam['idx']                          = _elm( $requests, 'idx' );
        $modelParam['d_result']                     = trim( _elm( $requests, 'textValue' ) );

        $this->db->transBegin();

        $aStatus                                    = $this->goodsModel->updateDahaeOptionCode( $modelParam );

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
        $logParam['MB_HISTORY_CONTENT']             = '다해 옵션 매칭코드 result 수정 - orgData:'.json_encode($aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).' // -> //'. json_encode($modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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
        $response['alert']                          = '';
        return $this->respond( $response );

    }

    public function dahaeOptionCodeDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();



        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];



        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $aData                                      = $this->goodsModel->getDahaeOptionCodeByIdx( _elm( $requests, 'idx' ) );


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

        $page_datas['detail']                       = view( '\Module\goods\Views\dahae\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function dahaeOptionCodeRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\dahae\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function dahaeOptionCodeRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_d_option_g_name' => [
                'label'  => 'i_d_option_g_name',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '옵션 그룹명 누락',
                ],
            ],
            'i_d_option_name' => [
                'label'  => 'i_d_option_name',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '옵션명 누락',
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
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response);
        }


        $modelParam                                 = [];
        $modelParam['d_option_g_name']              = _elm( $requests, 'i_d_option_g_name' );
        $modelParam['d_option_name']                = _elm( $requests, 'i_d_option_name' );
        $modelParam['d_result']                     = _elm( $requests, 'i_d_result', null, true );

        $this->db->transBegin();

        $aIdx                                       = $this->goodsModel->insertDahaeOptionCode( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['messages']                   = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '다해 옵션 매칭코드 등록 - data::'. json_encode($modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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
        $response['alert']                          = '등록되었습니다.';
        return $this->respond( $response );
    }


    public function dahaeOptionCodeDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'idx' => [
                'label'  => 'idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'idx 누락',
                ],
            ],
            'i_d_option_g_name' => [
                'label'  => 'i_d_option_g_name',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '옵션 그룹명 누락',
                ],
            ],
            'i_d_option_name' => [
                'label'  => 'i_d_option_name',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '옵션명 누락',
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
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response);
        }

        $aData                                      = $this->goodsModel->getDahaeOptionCodeByIdx( _elm( $requests, 'idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '잘못된 접근입니다.';

            return $this->respond( $response );
        }

        $modelParam                                 = [];
        $modelParam['idx']                          = _elm( $requests, 'idx' );
        $modelParam['d_option_g_name']              = _elm( $requests, 'i_d_option_g_name' );
        $modelParam['d_option_name']                = _elm( $requests, 'i_d_option_name' );
        $modelParam['d_result']                     = _elm( $requests, 'i_d_result' );

        $aStatus                                    = $this->goodsModel->updateDahaeOptionCode( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '수정 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '다해 옵션 매칭코드 수정 - orgData:'.json_encode($aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).' // -> //'. json_encode($modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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

    public function deleteDahaeOptionCode()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'idx' => [
                'label'  => 'idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '옵션 그룹명 누락',
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
            $response['messages']                   = $validation->getErrors();

            return $this->respond($response);
        }

        $aData                                      = $this->goodsModel->getDahaeOptionCodeByIdx( _elm( $requests, 'idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '잘못된 접근입니다.';

            return $this->respond( $response );
        }

        $modelParam                                 = [];
        $modelParam['idx']                          = _elm( $requests, 'idx' );

        $aStatus                                    = $this->goodsModel->deleteDahaeOptionCode( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '수정 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '다해 옵션 매칭코드 삭제 - orgData:'.json_encode($aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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
        $response['alert']                          = '삭제되었습니다.';
        return $this->respond( $response );
    }
}