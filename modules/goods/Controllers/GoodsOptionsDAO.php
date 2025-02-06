<?php
namespace Module\goods\Controllers;
use Module\goods\Models\GoodsModel;


class GoodsOptionsDAO
{
    private $goodsOptionsParam = [];

    private $O_IDX;                                 //'고유값',
    private $O_GOODS_IDX;                           //'상품번호',
    private $O_DAH_SPEC;                            //'다해 스펙번호',
    private $O_KEYS;                                //'키값',
    private $O_VALUES;                              //'내용값',
    private $O_ADD_PRICE;                           //'옵션추가금액',
    private $O_STOCK;                               //'옵션재고',
    private $O_VIEW_STATUS;                         //'노출상태',
    private $O_PRD_BARCODE;                         //'옵션상품 바코드',
    private $O_PRD_QRCODE;                          //'옵션상품 QR코드',
    private $O_CREATE_AT;                           //
    private $O_CREATE_IP;                           //
    private $O_CREATE_MB_IDX;                       //
    private $O_UPDATE_AT;                           //
    private $O_UPDATE_IP;                           //
    private $O_UPDATE_MB_IDX;                       //


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
            if ($key !== 'goodsOptionsParam') { // 필요에 따라 특정 변수 제외
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function save() {
        // 현재 설정된 프로퍼티만 goodsOptionsParam 추가
        foreach (get_object_vars($this) as $key => $value) {
            if ($value !== null && $key !== 'goodsOptionsParam') {
                $this->goodsOptionsParam[$key]      = $value;
            }
        }
        $orderModel                                 = new OrderModel();
        #------------------------------------------------------------------
        # TODO: update
        #------------------------------------------------------------------
        if( empty( $this->goodsOptionsParam['O_IDX'] ) === false ){
            $aStatus                                = $orderModel->updateGoodsOptionByIdx( $this->goodsOptionsParam );
        }
        #------------------------------------------------------------------
        # TODO: insert
        #------------------------------------------------------------------
        else{
            $aStatus                                = $orderModel->insertGoodsOption( $this->goodsOptionsParam );
        }
        return $aStatus;
    }
}