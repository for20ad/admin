<?php
namespace Module\goods\Controllers;
use Module\goods\Models\GoodsModel;

class GoodsInfoDAO
{
    private $goodsInfoParam = [];

    private $G_IDX;                                 //'상품고유값'
    private $G_PRID;                                //'상품코드'
    private $G_GODO_GOODSNO;                        //'고도몰 상품IDX',
    private $G_DAHAE_P_CODE;                        //'다해소프트 상품 코드',
    private $G_GROUP;                               //'그룹상품',
    private $G_GROUP_MAIN;                          //'그룹 메인상품',
    private $G_LOCAL_PRID;                          //'자체 상품코드',
    private $G_CATEGORY_MAIN;                       //'대표 상품카테고리 한글 ',
    private $G_CATEGORY_MAIN_IDX;                   //'대표상품 카테고리 IDX',
    private $G_CATEGORYS;                           //'카테고리 정보JSON',
    private $G_NAME;                                //'상품명',
    private $G_NAME_ENG;                            //'상품명 영문',
    private $G_SHORT_DESCRIPTION;                   //'상품 요약설명',
    private $G_CONTENT_PC;                          //'상품상세 설명 PC',
    private $G_CONTENT_MOBILE;                      //'상품상세 설명 MOBILE',
    private $G_CONTETN_IS_SAME_FLAG;                //'PC와 내용 동일 체크값',
    private $G_SEARCH_KEYWORD;                      //'검색키워드 ,로 분리',
    private $G_IS_PERFIT_FLAG;                      //'펄핏 사용유무',
    private $G_ADD_POINT;                           //'구매 적립포인트',
    private $G_SELL_PERIOD_START_AT;                //'판매시작일시',
    private $G_SELL_PERIOD_END_AT;                  //'판매종료일시',
    private $G_COLOR;                               //'제품색상',
    private $G_SELL_PRICE;                          //'소비자가',
    private $G_SELL_UNIT;                           //'판매단위',
    private $G_BUY_PRICE;                           //'공급가',
    private $G_PRICE;                               //'판매가',
    private $G_PRICE_RATE;                          //'마진율',
    private $G_TAX_TYPE;                            //'과세:Y,면세:N',
    private $G_DISCOUNT_CD;                         //'적용가능할인 '',''로 구분',
    private $G_SELL_POINT_FLAG;                     //'적립금 지급기준 Y:개별적립금, N:회원등급에 따른 기준',
    private $G_RELATION_GOODS_FLAG;                 //'연관상품 여부',
    private $G_RELATION_GOODS;                      //'연관상품 JSON',
    private $G_ADD_GOODS_FLAG;                      //'추가상품 여부',
    private $G_ADD_GOODS;                           //'추가상품 JSON',
    private $G_DELETE_STATUS;                       //'삭제상태',
    private $G_OPTION_USE_FLAG;                     //'옵션여부 Y:사용, N:미사용',
    private $G_STOCK_FLAG;                          //'N:무한정판매, Y:재고수량에 따름',
    private $G_STOCK_CNT;                           //'재고수량',
    private $G_SAFETY_STOCK;                        //'안전재고 수량',
    private $G_TEXT_OPTION_USE_FLAG;                //'텍스트 옵션 사용여부',
    private $G_TEXT_OPTION;                         //'텍스트 옵션 JSON형식',
    private $G_DELIVERY_PAY_CD;                     //'배송비 책정코드',
    private $G_DELIVERY_INFO;                       //'배송안내문구',
    private $G_EXCHANGE_INFO;                       //'교환안내문구',
    private $G_AS_INFO;                             //'AS안내문구',
    private $G_REFOUND_INFO;                        //'환불/회수안내문구',
    private $G_PC_OPEN_FLAG;                        //'PC노출상태',
    private $G_PC_SELL_FLAG;                        //'PC판매상태',
    private $G_MOBILE_OPEN_FLAG;                    //'모바일노출상태',
    private $G_MOBILE_SELL_FLAG;                    //'모바일판매상태',
    private $G_ORIGIN_NAME;                         //'원산지',
    private $G_MAKER_NAME;                          //'제조사',
    private $G_BRAND_IDX;                           //'브랜드 IDX',
    private $G_BRAND_NAME;                          //'브랜드 한글',
    private $G_SEO_TITLE;                           //'SEO 제목',
    private $G_SEO_DESCRIPTION;                     //'SEO 설명',
    private $G_ICON_IDXS;                           //'노출아이콘',
    private $G_ICON_PERIOD_START;                   //'아이콘 적용시작일',
    private $G_ICON_PERIOD_END;                     //'아이콘 적용 종료일',
    private $G_OUT_VIEW;                            //'외부노출값 NAVER,KAKAO',
    private $G_OUT_MAIN_IMG_PATH;                   //'외부노출 이미지 경로',
    private $G_OUT_MAIN_IMG_NAME;                   //'외부노출 이미지 명',
    private $G_OUT_GOODS_NAME;                      //'외부노출 상품명. 없으면 상품이름으로 노출',
    private $G_OUT_EVENT_TXT;                       //'외부노출 이벤트 문구',
    private $G_GOODS_CONDITION;                     //'상품상태',
    private $G_IS_SALES_TYPE;                       //'판매방식',
    private $G_GOODS_PRODUCT_TYPE;                  //'상품 유형',
    private $G_MIN_BUY_COUNT;                       //'최소구매수량',
    private $G_MEM_MAX_BUY_COUNT;                   //'1인 구매시 최대 수량',
    private $G_IS_ADULT_PRODUCT;                    //'성인용 구분',
    private $O_PRD_BARCODE;                         //'상품 바코드',
    private $O_PRD_QRCODE;                          //'QR코드',
    private $G_CREATE_AT;                           //
    private $G_CREATE_IP;                           //
    private $G_CREATE_MB_IDX;                       //
    private $G_UPDATE_AT;                           //
    private $G_UPDATE_IP;                           //
    private $G_UPDATE_MB_IDX;                       //


    // 하나의 set 메소드로 동적으로 값을 할당
    public function set($field, $value) {
        // 해당 필드가 클래스 내 존재하는지 확인 후 값 할당
        if (property_exists($this, $field)) {
            $this->$field                           = $value;
        } else {
            throw new Exception("{$field}는 유효하지 않은 필드입니다.");
        }
        return $this;
    }
    // get 메소드 - 동적으로 필드 값을 반환
    public function get($field) {
        if (property_exists($this, $field)) {
            return $this->$field;
        } else {
            throw new Exception("{$field}는 유효하지 않은 필드입니다.");
        }
    }

    // reset 메소드로 모든 프로퍼티 초기화
    public function reset() {
        foreach (get_object_vars($this) as $key => $value) {
            $this->$key                             = null;
        }
    }

    public function toArray() {
        // 모든 프로퍼티를 배열로 반환
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($key !== 'goodsInfoParam') { // 필요에 따라 특정 변수 제외
                $result[$key] = $value;
            }
        }
        return $result;
    }


    public function save() {
        // 현재 설정된 프로퍼티만 goodsInfoParam 추가
        foreach (get_object_vars($this) as $key => $value) {
            if ($value !== null && $key !== 'goodsInfoParam') {
                $this->goodsInfoParam[$key]         = $value;
            }
        }

        $orderModel                                 = new OrderModel();
        #------------------------------------------------------------------
        # TODO: update
        #------------------------------------------------------------------
        if( empty( $this->goodsInfoParam['G_IDX'] ) === false ){
            $aStatus                                = $orderModel->updateGoodsInfoByIdx( $this->goodsInfoParam );
        }
        #------------------------------------------------------------------
        # TODO: insert
        #------------------------------------------------------------------
        else{
            $aStatus                                = $orderModel->insertGoodsInfo( $this->goodsInfoParam );
        }
        return $aStatus;
    }
}