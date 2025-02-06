<?php
namespace Module\dahae\Controllers;
use Module\core\Controllers\CoreController;
use Module\dahae\Models\DahaeModel;
use Config\Services;
use DOMDocument;
use Module\dahae\Config\Config as DahaeConfig;

use Module\goods\Controllers\GoodsInfoDAO;
use Module\goods\Controllers\GoodsOptionsDAO;

use Module\goods\Models\BrandModel;
use Module\goods\Models\CategoryModel;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use CodeIgniter\HTTP\Files\UploadedFile;

use Exception;


use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;


use League\HTMLToMarkdown\HtmlConverter;


class Dahae extends CoreController
{
    protected $dahaeConfig;
    protected $dModel, $brandModel, $cateModel;
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->dahaeConfig                          = new DahaeConfig();
        $this->dModel                               = new DahaeModel();
        $this->brandModel                           = new BrandModel();
        $this->cateModel                            = new CategoryModel();
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

    public function getProductHeaders()
    {
        ini_set('memory_limit', '-1'); // 무제한 설정

        $jwtToken                                   = $this->getAuthToken();


        $aConfig                                    = $this->dahaeConfig->dahae;
        $url                                        = _elm( _elm( $aConfig, 'apiUrl' ), 'modelHeader' );
        $optionUrl                                  = _elm( _elm( $aConfig, 'apiUrl' ), 'modelDetail' );
        $param                                      = [
            'page'                                  => 1,
            'size'                                  => 5,
            //'modelCode'                             => 'M103977',
        ];

        $converter                                  = new HtmlConverter();


        //킨 재스퍼 락 SP
        #------------------------------------------------------------------
        # TODO: POST
        #------------------------------------------------------------------
        $response = $this->_curl( $url, 'GET', $jwtToken, $param);
        $totalCount = _elm( $response, 'totalCount' );
        echo "<pre>";
        echo "상품 총 :: ".$totalCount .PHP_EOL;
        echo "페이지 수 :: ".ceil(  $totalCount/ _elm( $param, 'size' ) ) .PHP_EOL;
        echo "</pre>";

        $this->db->transBegin();
        $data     = json_decode( _elm( $response, 'process' ), true );
        if( empty( $data ) === false ){

            #------------------------------------------------------------------
            # TODO: 상품 DAO
            #------------------------------------------------------------------
            $gInfoDao                               = new GoodsInfoDAO();
            #------------------------------------------------------------------
            # TODO: 상품 옵션 DAO
            #------------------------------------------------------------------
            $gOptionDao                             = new GoodsOptionsDAO();

            foreach( $data as $key => $dahaeProduct ){
                $optData                            = $this->getProductDetail( $jwtToken, _elm( $dahaeProduct, 'ModelCode' ) );


                $data[$key]['optInfo']              = $optData;

                // $modelParam = [];
                $esGoodsInfo                        = $this->dModel->getEsGoodsByIdx( _elm( $dahaeProduct, 'GodomallCode' ) );

                if( empty( $esGoodsInfo ) === false ){
                    $gInfoDao->set( 'G_PRID',                       _uniqid( 12, true ) );                     //'상품코드'
                    $gInfoDao->set( 'G_GODO_GOODSNO',               _elm( $dahaeProduct, 'GodomallCode' ) );         //'고도몰 상품IDX',
                    $gInfoDao->set( 'G_DAHAE_P_CODE',               _elm( $dahaeProduct, 'ModelCode' ) );            //'다해소프트 상품 코드',



                    $gInfoDao->set( 'G_NAME',                       _elm( $esGoodsInfo, 'goodsNm', null, true ) ?? _elm( $dahaeProduct, 'ModelName' ) );       //'상품명',

                    $gInfoDao->set( 'G_NAME_ENG',                   _elm( $esGoodsInfo, 'goodsEngNm', null, true ) ?? _elm( $dahaeProduct, 'MainProductName' ) );  //'상품명 영문',

                    $gInfoDao->set( 'G_SHORT_DESCRIPTION',          $converter->convert( _elm( $esGoodsInfo, 'shortDescription' ) ) );            //'상품 요약설명',
                    $gInfoDao->set( 'G_CONTETN_IS_SAME_FLAG',       strtoupper( _elm( $esGoodsInfo, 'goodsDescriptionSameFl' ) ) );               //'PC와 내용 동일 체크값',
                    $gInfoDao->set( 'G_CONTENT_PC',
                        str_replace(
                            'https://lundhags.speedgabia.com/',
                            'https://admin.brav.co.kr/upload/gabia/',
                            $converter->convert( _elm( $esGoodsInfo, 'goodsDescription' ) )
                        )
                    );            //'상품상세 설명 PC',
                    if( _elm( $esGoodsInfo, 'goodsDescriptionSameFl' )  == 'y' ){
                        $gInfoDao->set( 'G_CONTENT_MOBILE',
                            str_replace(
                                'https://lundhags.speedgabia.com/',
                                'https://admin.brav.co.kr/upload/gabia/',
                                $converter->convert( _elm( $esGoodsInfo, 'goodsDescription' ) )
                            )
                        );            //'상품상세 설명 MOBILE',
                    }else{
                        $gInfoDao->set( 'G_CONTENT_MOBILE',
                            str_replace(
                                'https://lundhags.speedgabia.com/',
                                'https://admin.brav.co.kr/upload/gabia/',
                                $converter->convert( _elm( $esGoodsInfo, 'goodsDescriptionMobile' ) )
                            )
                        );      //'상품상세 설명 MOBILE',
                    }

                    $gInfoDao->set( 'G_SEARCH_KEYWORD',             _elm( $dahaeProduct, 'SearchKeywords', null, true ) ?? _elm( $esGoodsInfo, 'goodsSearchWord' ) ); //'검색키워드 ,로 분리',
                    $gInfoDao->set( 'G_IS_PERFIT_FLAG',             strtoupper( _elm( $esGoodsInfo, 'perfitUse' ) ) );                             //'펄핏 사용유무',
                    $gInfoDao->set( 'G_ADD_POINT',                  0);                                                                            //'구매 적립포인트',

                    $gInfoDao->set( 'G_SELL_PERIOD_START_AT',       _elm( $esGoodsInfo, 'salesStartYmd' )?? '0000-00-00' );                        //'판매시작일시',
                    $gInfoDao->set( 'G_SELL_PERIOD_END_AT',         _elm( $esGoodsInfo, 'salesEndYmd' )?? '0000-00-00' );                            //'판매종료일시',

                    #------------------------------------------------------------------
                    # TODO: 색상은 option에서 정하도록 함.
                    #------------------------------------------------------------------

                    $gInfoDao->set( 'G_SELL_PRICE',                 _elm( $esGoodsInfo, 'fixedPrice' ) );                 //'소비자가',
                    $gInfoDao->set( 'G_SELL_UNIT',                  'EA' );                                               //'판매단위',
                    $gInfoDao->set( 'G_BUY_PRICE',                  _elm( $esGoodsInfo, 'costPrice' ) );                  //'공급가',
                    $gInfoDao->set( 'G_PRICE',                      _elm( $esGoodsInfo, 'goodsPrice' ) );                 //'판매가',
                    $gInfoDao->set( 'G_PRICE_RATE',   round( ( (_elm( $esGoodsInfo, 'fixedPrice' ) - _elm( $esGoodsInfo, 'goodsPrice' ) ) / _elm( $esGoodsInfo, 'fixedPrice' ) ) * 100, 2) ); //'마진율',
                    $gInfoDao->set( 'G_TAX_TYPE',                   'Y' );                                                //'과세:Y,면세:N',
                    $G_DISCOUNT_CD                                      = [
                        'coupon','point'
                    ];
                    #------------------------------------------------------------------
                    # TODO: 제외 적용 베네핏 있으면 배열에서 삭제
                    #------------------------------------------------------------------
                    if( empty( _elm( $esGoodsInfo, 'exceptBenefit' ) ) === false ){
                        $exceptBenefit                         = explode( '^|^', _elm( $esGoodsInfo, 'exceptBenefit' ) );
                        if( in_array( 'coupon', $exceptBenefit ) ){
                            $G_DISCOUNT_CD                     = array_diff($G_DISCOUNT_CD, ['coupon']);
                        }
                        if( in_array( 'mileage', $exceptBenefit ) ){
                            $G_DISCOUNT_CD                     = array_diff($G_DISCOUNT_CD, ['point']);
                        }
                    }
                    $gInfoDao->set( 'G_DISCOUNT_CD',           implode(',',$G_DISCOUNT_CD) );                                                      //'적용가능할인 '',''로 구분',


                    #------------------------------------------------------------------
                    # TODO: 관련상품 데이터
                    #------------------------------------------------------------------
                    if( empty( _elm( $esGoodsInfo, 'relationGoodsNo' ) ) === false ){
                        $gInfoDao->set( 'G_RELATION_GOODS_FLAG','Y' );                                                                            //'연관상품 여부',
                        $relationGoods                          = explode( "||", _elm( $esGoodsInfo, 'relationGoodsNo' ) );
                        $relationEach                           = explode( "^|", _elm( $esGoodsInfo, 'relationGoodsEach' ) );
                        #------------------------------------------------------------------
                        # TODO: 연관상품 수량 체크
                        #------------------------------------------------------------------
                        //echo "연관상품 수량체크 ::::::::::::: ".count($relationGoods)."::".count( $relationEach );
                        //if( count($relationGoods) ===  count( $relationEach ) ){
                            $relationDatas                       = [];
                            foreach( $relationGoods as $nKey => $nRelation ){
                                $relationDatas[$nKey]['g_idx']    = $nRelation;
                                $relationDatas[$nKey]['g_add_gbn']= strpos( _elm( $relationEach, $nKey ), 'y' ) !== false ? 'dup': null;

                            }
                            $gInfoDao->set( 'G_RELATION_GOODS',  json_encode( $relationDatas , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) );                                                                               //'연관상품 JSON',
                        //}

                    }


                    // $modelParam['G_ADD_GOODS_FLAG']                                         ;//'추가상품 여부',
                    // $modelParam['G_ADD_GOODS']                                              ;//'추가상품 JSON',
                    $gInfoDao->set( 'G_DELETE_STATUS', 'N');
                    if( _elm( $esGoodsInfo, 'delDt' ) != '0000-00-00 00:00:00' ){
                        $gInfoDao->set( 'G_DELETE_STATUS', 'Y');
                    }

                    $gInfoDao->set( 'G_TEXT_OPTION_USE_FLAG', 'N');
                    $gInfoDao->set( 'G_DELIVERY_PAY_CD', 'od');
                    $gInfoDao->set( 'G_PC_OPEN_FLAG', 'Y');
                    $gInfoDao->set( 'G_PC_SELL_FLAG', 'Y');
                    $gInfoDao->set( 'G_MOBILE_OPEN_FLAG', 'Y');
                    $gInfoDao->set( 'G_MOBILE_SELL_FLAG', 'Y');
                    $gInfoDao->set( 'G_ORIGIN_NAME',  _elm($dahaeProduct, 'ModelOrigin', null, true ) ?? _elm( $esGoodsInfo, 'originNm' ) );
                    $gInfoDao->set( 'G_MAKER_NAME',  _elm($dahaeProduct, 'ModelBrandName', null, true ) ?? _elm( $esGoodsInfo, 'makerNm' ) );

                    $brandInfo = $this->brandModel->getBrandInfoByBrandName( _elm($dahaeProduct, 'BrandCatBig', null, true ) );
                    if( empty( $brandInfo ) === false ){
                        $gInfoDao->set( 'G_BRAND_IDX', _elm( $brandInfo, 'C_IDX' ) );
                        $gInfoDao->set( 'G_BRAND_NAME', _elm( $brandInfo, 'C_BRAND_NAME' ) );
                    }

                    if( _elm( $esGoodsInfo, 'seoTagFl' ) == 'y' ){
                        $seoTagInfo                      = $this->dModel->getEsSeoInfo( _elm( $esGoodsInfo, 'seoTagSno' ) );
                        if( empty( $seoTagInfo ) === false ){
                            $gInfoDao->set( 'G_SEO_TITLE', _elm( $seoTagInfo, 'title' ) );
                            $gInfoDao->set( 'G_SEO_DESCRIPTION', _elm( $seoTagInfo, 'description' ) );
                        }
                    }

                    // $modelParam['G_ICON_IDXS']                                              ;//'노출아이콘',
                    // $modelParam['G_ICON_PERIOD_START']                                      ;//'아이콘 적용시작일',
                    // $modelParam['G_ICON_PERIOD_END']                                        ;//'아이콘 적용 종료일',

                    // $modelParam['G_OUT_VIEW']                                               ;//'외부노출값 NAVER,KAKAO',
                    // $modelParam['G_OUT_MAIN_IMG_PATH']                                      ;//'외부노출 이미지 경로',
                    // $modelParam['G_OUT_MAIN_IMG_NAME']                                      ;//'외부노출 이미지 명',
                    // $modelParam['G_OUT_GOODS_NAME']                                         ;//'외부노출 상품명. 없으면 상품이름으로 노출',
                    // $modelParam['G_OUT_EVENT_TXT']                                          ;//'외부노출 이벤트 문구',


                    $gInfoDao->set( 'G_GOODS_CONDITION', 'new' );
                    $gInfoDao->set( 'G_IS_SALES_TYPE', _elm( $dahaeProduct, 'ItemGroupName' ) == '위탁재고상품' ? 'consignment_stock':'' );


                    // $modelParam['G_GOODS_PRODUCT_TYPE']                                     ;//'상품 유형',
                    $gInfoDao->set( 'G_MIN_BUY_COUNT', _elm( $esGoodsInfo, 'minOrderCnt' ) );
                    $gInfoDao->set( 'G_MEM_MAX_BUY_COUNT', _elm( $esGoodsInfo, 'maxOrderCnt' ) );
                    $gInfoDao->set( 'G_IS_ADULT_PRODUCT', strtoupper( _elm( $esGoodsInfo, 'onlyAdultFl' ) ) );


                    $gInfoDao->set( 'G_CREATE_AT', _elm( $esGoodsInfo, 'regDt' ) );
                    $gInfoDao->set( 'G_UPDATE_AT', _elm( $esGoodsInfo, 'modDt' ) );

                    // $modelParam['G_LOCAL_PRID']                                             ;//'자체 상품코드',

                    #------------------------------------------------------------------
                    # TODO: 카테고리 데이터가 명확하지 않아 지금 변경하지 않음.
                    #------------------------------------------------------------------
                    if( empty( _elm( $esGoodsInfo, 'newCateCd' ) ) === false ){
                        $newCateCd  = explode( ',', _elm( $esGoodsInfo, 'newCateCd' ) );
                        if( empty( $newCateCd ) === false ){
                            $cateResult = [];
                            foreach( $newCateCd as $cKey => $cateCd ){
                                $cateData = $this->cateModel->getCategoryDataByCode( $cateCd );
                                if( empty( $cateData ) == false ){
                                    if( $cKey == 0 ){
                                        $gInfoDao->set( 'G_CATEGORY_MAIN_IDX', _elm( $cateData, 'C_IDX' ) );
                                        $cateTopFullName = $this->cateModel->getCateTopNameJoin( _elm( $cateData, 'C_IDX' ) );

                                        $gInfoDao->set( 'G_CATEGORY_MAIN', _elm( $cateTopFullName, 'FullCategoryName' ) );
                                    }
                                    $cateResult[$cKey] = [
                                        'C_IDX' => _elm( $cateData, 'C_IDX' ),
                                        'C_CATE_NAME'=> _elm( $cateData, 'C_CATE_NAME' ),
                                    ];
                                }
                            }
                            $gInfoDao->set( 'G_CATEGORYS',  json_encode( $cateResult , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) );
                        }
                    }

                    #------------------------------------------------------------------
                    # TODO: 재고수량
                    #------------------------------------------------------------------
                    // $modelParam['G_OPTION_USE_FLAG']                                        ;//'옵션여부 Y:사용, N:미사용',
                    // $modelParam['G_STOCK_FLAG']                                             ;//'N:무한정판매, Y:재고수량에 따름',
                    // $modelParam['G_STOCK_CNT']                                              ;//'재고수량',
                    // $modelParam['G_SAFETY_STOCK']                                           ;//'안전재고 수량',

                     // 기본 데이터 배열화 후 저장
                    $defaultData = $gInfoDao->toArray();
                    $resultData = [];
                    $colorKeywords = ['색상', '컬러', '모델,색상', '색상,사이즈', '색상/길이', '색상/사이즈', '색상;사이즈', '색상선택', '성별/색상', '크기/색상']; // 색상을 나타내는 키워드 배열
                    $resultData = [];
                    foreach ($optData as $option) {
                        $color = '기타'; // 기본 색상

                        $optionGroupName1                                                  = $this->dModel->getOptionGroupName( _elm( $option, 'Option1' ) );
                        $optionGroupName2                                                  = $this->dModel->getOptionGroupName( _elm( $option, 'Option2' ) );

                        if ( !empty($optionGroupName1) && in_array(mb_strtolower($optionGroupName1), $colorKeywords, true)) {
                            $color = $option['Option1'] ?? '기타';
                        } elseif ( !empty($optionGroupName2) &&in_array(mb_strtolower($optionGroupName2), $colorKeywords, true)) {
                            $color = $option['Option2'] ?? '기타';
                        }

                        if (!isset($resultData[$color])) {
                            $resultData[$color] = [
                                'baseData' => $defaultData, // 기본 데이터 복사
                                'options' => []
                            ];
                        }

                        $existingOptionKey = null;
                        foreach ($resultData[$color]['options'] as $index => $existingOption) {
                            if ($existingOption['O_VALUES'] === $option['Option2']) {
                                $existingOptionKey = $index;
                                break;
                            }
                        }

                        if ($existingOptionKey !== null) {
                            // 같은 O_VALUES가 존재하면 O_STOCK 값을 더함
                            $resultData[$color]['options'][$existingOptionKey]['O_STOCK'] += $option['SellAbleJego'];
                        } else {
                            // 새로운 옵션 추가
                            $resultData[$color]['options'][] = [
                                'O_KEYS' => $dahaeProduct['OptionGroup2'],
                                'O_VALUES' => $option['Option2'],
                                'O_ADD_PRICE' => $option['OptionAdditionalPrice'],
                                'O_STOCK' => $option['SellAbleJego'],
                                'O_PRD_BARCODE' => $option['ItemBarcode'],
                                'O_DAH_SPEC' => $option['ItemSpec'],
                            ];
                        }
                    }

                    #------------------------------------------------------------------
                    # TODO: 웁션 돌리고 처리해야 되는 것들
                    #------------------------------------------------------------------


                    #------------------------------------------------------------------
                    # TODO: 저장 후 처리
                    #------------------------------------------------------------------
                    #------------------------------------------------------------------
                    # TODO:  할인 그룹 데이터 저장 (예: 적립금 관련 데이터)
                    #------------------------------------------------------------------
                    // if (_elm($esGoodsInfo, 'goodsDiscountGroup') !== 'all') {
                    //     $gDiscountData = [
                    //         'insertID' => $insertID, // 첫 번째 삽입된 데이터의 ID 참조
                    //         'G_SELL_POINT_FLAG' => 'Y',
                    //         // 기타 필요한 데이터 추가
                    //     ];

                    //     // 할인 그룹 관련 테이블에 데이터 삽입
                    //     $gDiscountModel->insert($gDiscountData);
                    // }


                }
                $nLoop = 0;
                $groupData = [];
                foreach ($resultData as $color => $colorData) {

                    $colorData['baseData']['G_COLOR'] = $this->dModel->getOptionGroupColor( $color );

                    $groupMainIdx = null;

                    $aIdx = $this->dModel->insertTmpGoods(  $colorData['baseData'] );

                    if ( $this->db->transStatus() === false || $aIdx === false ) {
                        $this->db->transRollback();
                        $response['status']                     = 400;
                        $response['alert']                      = 'baseData 저장 처리중 오류발생.. 다시 시도해주세요.';
                        echo "<pre>";
                        print_r($response);
                        echo "</pre>";
                    }

                    if ($nLoop === 0) {
                        $groupMainIdx = $aIdx;
                        $groupData[] = [
                            'g_idx' => $aIdx,
                            'g_is_main' => 'Y'
                        ];
                    } else {
                        $groupData[] = [
                            'g_idx' => $aIdx,
                            'g_is_main' => 'N'
                        ];
                    }

                    foreach( _elm( $colorData, 'options' ) as $oKey => $option ){
                        $optionData = $option;
                        $optionData['O_GOODS_IDX'] = $aIdx;
                        $oIdx = $this->dModel->insertTmpGoodsOptions( $optionData );

                        if ( $this->db->transStatus() === false || $oIdx === false ) {
                            $this->db->transRollback();
                            $response['status']                     = 400;
                            $response['alert']                      = 'option 저장 처리중 오류발생.. 다시 시도해주세요.';
                            echo "<pre>";
                            print_r($response);
                            echo "</pre>";
                        }
                    }


                    $nLoop ++;
                }
                #------------------------------------------------------------------
                # TODO: 인서트 이후 업데이트 처리
                #------------------------------------------------------------------
                $groupJson = json_encode($groupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                foreach ($groupData as $group) {
                    $upParam = [];
                    $upParam['G_IDX'] = $group['g_idx'];
                    $upParam['G_GROUP'] = $groupJson;
                    $upParam['G_GROUP_MAIN'] = $group['g_is_main'];
                    $uStatus = $this->dModel->updateTmpGoods($upParam);
                    if ( $this->db->transStatus() === false || $uStatus === false ) {
                        $this->db->transRollback();
                        $response['status']                     = 400;
                        $response['alert']                      = '그룹 저장 처리중 오류발생.. 다시 시도해주세요.';
                        echo "<pre>";
                        print_r($response);
                        echo "</pre>";
                    }
                    #------------------------------------------------------------------
                    # TODO: 이미지 세팅
                    #------------------------------------------------------------------
                    $_imgageDatas = $this->dModel->getGodoImages( _elm( $dahaeProduct, 'GodomallCode' ) );
                    $_config                                     = [
                        'path' => 'goodsTest/product/'.$group['g_idx'],
                        'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
                    ];
                    if( empty( $_imgageDatas ) === false ){
                        foreach( $_imgageDatas as $iKey => $_images ){
                            $filePath = WRITEPATH.'uploads/godo_data/goods/'. _elm( $esGoodsInfo, 'imagePath' )._elm( $_images, 'imageName' ) ;

                            if( file_exists( $filePath ) ){
                                $imgParam                           = [];
                                $file = new UploadedFile(
                                    $filePath,
                                    basename($filePath),
                                    mime_content_type($filePath) ?: 'application/octet-stream', // MIME 타입 기본값 설정
                                    filesize($filePath),
                                    UPLOAD_ERR_OK
                                );
                                $file_return                        = $this->_uploadAndResize( $filePath, $_config );

                                #------------------------------------------------------------------
                                # TODO: 파일처리 실패 시
                                #------------------------------------------------------------------
                                if( _elm($file_return , 'status') === false ){
                                    $this->db->transRollback();
                                    $response['status']             = 400;
                                    $response['alert']              = _elm( $file_return, 'error' );

                                    echo "<pre>";
                                    print_r($response);
                                    echo "</pre>";
                                }
                                $fileSize                           = _elm( $file_return, 'size' );
                                $fileExtension                      = _elm( $file_return, 'ext' );
                                $fileMimeType                       = _elm( $file_return, 'type' );

                                #------------------------------------------------------------------
                                # TODO: 데이터모델 세팅
                                #------------------------------------------------------------------

                                $imgParam['I_SORT']                 = $iKey + 1;
                                $imgParam['I_GOODS_IDX']            = $group['g_idx'];
                                $imgParam['I_IMG_NAME']             = _elm( $file_return, 'org_name');
                                $imgParam['I_IMG_PATH']             = _elm( $file_return, 'uploaded_path');
                                $imgParam['I_IMG_VIEW_SIZE']        = '';
                                $imgParam['I_IS_ORIGIN']            = 'Y';
                                $imgParam['I_IMG_SIZE']             = $fileSize;
                                $imgParam['I_IMG_EXT']              = $fileExtension;
                                $imgParam['I_IMG_MIME_TYPE']        = $fileMimeType;
                                $imgParam['I_CREATE_AT']            = date( 'Y-m-d H:i:s' );
                                $imgParam['I_CREATE_IP']            = $this->request->getIPAddress();
                                $imgParam['I_CREATE_MB_IDX']        = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                                $imgIdx                             = $this->dModel->insertGoodsImages( $imgParam );
                                if ( $this->db->transStatus() === false || $imgIdx == false ) {
                                    $this->db->transRollback();
                                    $response['status']             = 400;
                                    $response['alert']              = '이미지 처리중 오류발생.. 다시 시도해주세요.';
                                    echo "<pre>";
                                    print_r($response);
                                    echo "</pre>";
                                }
                                #------------------------------------------------------------------
                                # TODO: 리사이즈된 이미지 저장
                                #------------------------------------------------------------------
                                $resizedImages = _elm($file_return, 'resized');
                                if (!empty($resizedImages)) {
                                    foreach ($resizedImages as $resized) {
                                        $resizeParam = [
                                            'I_SORT'                => $iKey + 1,
                                            'I_GOODS_IDX'           => $group['g_idx'],
                                            'I_IMG_NAME'            => _elm( $file_return, 'org_name'),
                                            'I_IMG_PATH'            => _elm($resized, 'path'),
                                            'I_IMG_VIEW_SIZE'       => _elm($resized, 'size'),
                                            'I_IMG_SIZE'            => filesize($resized['path']),
                                            'I_IMG_EXT'             => $fileExtension,
                                            'I_IMG_MIME_TYPE'       => $fileMimeType,
                                            'I_IS_ORIGIN'           => 'N', // 리사이즈된 이미지 플래그 설정
                                            'I_CREATE_AT'           => date('Y-m-d H:i:s'),
                                            'I_CREATE_IP'           => $this->request->getIPAddress(),
                                            'I_CREATE_MB_IDX'       => _elm($this->session->get('_memberInfo'), 'member_idx')
                                        ];

                                        $resizeImgIdx = $this->dModel->insertGoodsImages($resizeParam);
                                        if ($this->db->transStatus() === false || $resizeImgIdx == false) {
                                            $this->db->transRollback();
                                            $response['status'] = 400;
                                            $response['alert'] = '리사이즈 이미지 처리중 오류발생.. 다시 시도해주세요.';

                                            echo "<pre>";
                                            print_r($response);
                                            echo "</pre>";
                                        }
                                    }
                                }



                            }
                        }
                    }


                    $this->db->transCommit();
                }





            }

        }




        echo PHP_EOL."================================================================================".PHP_EOL;



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
        echo $_urlParams;

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




    protected function _uploadAndResize($filePath, $_config)
    {
        $uploader = Services::upload();

        // 기본 설정 및 사용자 정의 설정 병합
        $config = [
            'upload_path' =>  WRITEPATH . 'uploads/' . _elm($_config, 'path'),
            'allowed_types' => _elm($_config, 'mimes') ?? 'jpg|jpeg|png|gif',
            'max_size' => _elm($_config, 'max_size') ?? '10240', // KB 단위
        ];

        $file = new UploadedFile(
            $filePath,
            basename($filePath),
            mime_content_type($filePath) ?: 'application/octet-stream',
            filesize($filePath),
            UPLOAD_ERR_OK
        );

        // 파일 크기 검사
        if ($file->getSize() > ($config['max_size'] * 1024)) { // KB 단위로 비교
            return [
                'status' => false,
                'error' => '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB'
            ];
        }


        // 업로드 경로 생성
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        // 로컬 파일을 UploadedFile 객체로 변환
        $filePath = realpath($file);
        if ($filePath === false) {
            return [
                'status' => false,
                'error'  => '유효하지 않은 파일 경로입니다.'
            ];
        }



        $originalName = $file->getName();
        $realFilename = $file->getRandomName();

        // 파일 업로드

        if (!is_dir($config['upload_path'])) {
            if (!mkdir($config['upload_path'], 0755, true)) {
                return [
                    'status' => false,
                    'error'  => "업로드 경로 생성 실패: " . $config['upload_path']
                ];
            }
        }

        if (!is_writable($config['upload_path'])) {
            return [
                'status' => false,
                'error'  => "업로드 경로에 쓰기 권한이 없습니다: " . $config['upload_path']
            ];
        }

        if (!copy($filePath, $config['upload_path'] . '/' . $realFilename)) {
            return [
                'status' => false,
                'error'  => '파일 복사 실패'
            ];
        }

        $uploadedPath = $config['upload_path'] . '/' . $realFilename;
        $relativePath = 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;

        // 리사이즈할 사이즈 목록
        $sizes = [
            [100, 100],
            [250, 250],
            [500, 500],
            [800, 800],
            [1024, 1024],
        ];

        $resizedImages = $this->_resizeImage($uploadedPath, $relativePath, $sizes, $config['upload_path'], $realFilename, $_config);

        // 업로드된 파일 및 리사이즈된 파일 정보 반환
        return [
            'status' => true,
            'org_name' => $originalName,
            'url' => base_url($relativePath),
            'uploaded_path' => $relativePath,
            'type' => mime_content_type($uploadedPath),
            'size' => filesize($uploadedPath), // bytes 단위
            'ext' => pathinfo($uploadedPath, PATHINFO_EXTENSION),
            'resized' => $resizedImages, // 리사이즈된 이미지 정보
        ];
    }


    protected function _resizeImage($uploadedPath, $relativePath, $sizes, $uploadPath, $realFilename, $_config)
    {
        $resizedImages = [];
        foreach ($sizes as $size) {
            $width = $size[0];
            $height = $size[1];
            $resizedFilename = pathinfo($realFilename, PATHINFO_FILENAME) . "_{$width}x{$height}." . pathinfo($realFilename, PATHINFO_EXTENSION);
            $resizedPath = $uploadPath . '/' . $resizedFilename;

            // 리사이즈 작업 수행
            if ($this->_performResize($uploadedPath, $resizedPath, $width, $height)) {
                $resizedImages[] = [
                    'path' => $resizedPath,
                    'size' => "{$width}x{$height}"
                ];
            }
        }

        return $resizedImages;
    }

    protected function _performResize($sourcePath, $destPath, $width, $height)
    {
        // 여기에서 리사이즈 처리 로직 구현 (예: GD, ImageMagick 등 사용)
        // 성공 시 true 반환, 실패 시 false 반환

        // 예시 코드: GD 라이브러리 사용
        $sourceImage = imagecreatefromstring(file_get_contents($sourcePath));
        if ($sourceImage === false) {
            return false;
        }

        $resizedImage = imagescale($sourceImage, $width, $height);
        if ($resizedImage === false) {
            imagedestroy($sourceImage);
            return false;
        }

        $extension = pathinfo($destPath, PATHINFO_EXTENSION);
        $result = false;
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $result = imagejpeg($resizedImage, $destPath);
        } elseif ($extension === 'png') {
            $result = imagepng($resizedImage, $destPath);
        } elseif ($extension === 'gif') {
            $result = imagegif($resizedImage, $destPath);
        }

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return $result;
    }


}
