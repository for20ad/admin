<?php
namespace Module\promotion\Models;

use Config\Services;
use CodeIgniter\Model;

class CouponModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getCouponLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON C' );
        $builder->select( 'C.*' );
        $builder->select( ' COUNT(CI.I_IDX) AS TOTAL_COUNT' );
        $builder->select( 'SUM(CASE WHEN CI.I_STATUS = \'Y\' THEN 1 ELSE 0 END) AS USED_COUNT' );
        $builder->select( 'SUM(CASE WHEN CI.I_STATUS = \'N\' THEN 1 ELSE 0 END) AS UNUSED_COUNT ' );


        $builder->join( 'COUPON_ISSUE CI',          'C.C_IDX = CI.I_CPN_IDX', 'left' );
        if( !empty( _elm( $param, 'C_NAME' ) ) ){
            $builder->like( 'C_NAME',               _elm( $param, 'C_NAME' ), 'both' );
        }
        if( !empty( _elm( $param, 'C_STATUS' ) ) ){
            $builder->where( 'C_STATUS',            _elm( $param, 'C_STATUS' ) );
        }
        if( !empty( _elm( $param, 'C_SCOPE_GBN' ) ) ){
            $builder->where( 'C_SCOPE_GBN',         _elm( $param, 'C_SCOPE_GBN' ) );
        }

        if( !empty( _elm( $param, 'S_START_DATE' ) ) && !empty( _elm( $param, 'S_END_DATE' ) ) ){
            if( _elm( $param, 'S_DATE_CONFITION' ) == 'C_PERIOD' ){
                $builder->where( 'C_PERIDO_LIMIT',  'N' );
                $builder->where( 'C_PERIOD_START_AT >= ', _elm( $param, 'S_START_DATE' ) );
                $builder->where( 'C_PERIOD_END_AT <= ', _elm( $param, 'S_END_DATE' ) );
            }else{
                $builder->where( _elm( $param, 'S_DATE_CONFITION' ) .' >= ', _elm( $param, 'S_START_DATE' ) );
                $builder->where( _elm( $param, 'S_DATE_CONFITION' ) .' <= ', _elm( $param, 'S_END_DATE' ) );
            }
        }
        if( !empty( _elm( $param, 'C_PUB_GBN' ) ) ){
            $builder->where( 'C_PUB_GBN', _elm( $param, 'C_PUB_GBN' ) );
        }



        $builder->groupBy( 'C.C_IDX' );
        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }
          // 총 결과 수
          $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음
        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();

        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getCouponDataByIdx( $cpnIdx )
    {
        $aReturn                                    = [];
        if( empty( $cpnIdx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'COUPON' );
        $builder->where( 'C_IDX',                   $cpnIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCouponInfo( $cpn_idx )
    {
        $aReturn                                    = [];
        if( empty( $cpn_idx ) ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON' );
        $builder->where( 'C_IDX',                   $cpn_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function ChangeCouponStatus( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'COUPON' );
        $builder->set( 'C_STATUS',                  _elm( $param, 'C_STATUS' ) );
        $builder->where( 'C_IDX',                   _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function insertCoupon( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON' );
        $builder->set( 'C_NAME',                    _elm( $param, 'C_NAME' ) );
        $builder->set( 'C_DISCRIPTION',             _elm( $param, 'C_DISCRIPTION' ) );
        $builder->set( 'C_PUB_GBN',                 _elm( $param, 'C_PUB_GBN' ) );
        $builder->set( 'C_TARGET',                  _elm( $param, 'C_TARGET' ) );
        $builder->set( 'C_TARGET_GRADE_IDX',        _elm( $param, 'C_TARGET_GRADE_IDX' ) );
        $builder->set( 'C_ISSUE_NO_LIMIT_FLAG',     _elm( $param, 'C_ISSUE_NO_LIMIT_FLAG' ) );
        $builder->set( 'C_ISSUE_LIMIT',             _elm( $param, 'C_ISSUE_LIMIT' ) );
        $builder->set( 'C_NOTI_FLAG',               _elm( $param, 'C_NOTI_FLAG' ) );
        $builder->set( 'C_BENEFIT_GBN',             _elm( $param, 'C_BENEFIT_GBN' ) );
        $builder->set( 'C_BENEFIT',                 _elm( $param, 'C_BENEFIT' ) );
        $builder->set( 'C_BENEFIT_UNIT',            _elm( $param, 'C_BENEFIT_UNIT' ) );
        $builder->set( 'C_MIN_PRICE',               _elm( $param, 'C_MIN_PRICE' ) );
        $builder->set( 'C_SCOPE_GBN',               _elm( $param, 'C_SCOPE_GBN' ) );
        $builder->set( 'C_EXCEPT_PRODUCT_IDXS',     _elm( $param, 'C_EXCEPT_PRODUCT_IDXS' ) );
        $builder->set( 'C_PICK_ITEMS',              _elm( $param, 'C_PICK_ITEMS' ) );
        $builder->set( 'C_PERIDO_LIMIT',            _elm( $param, 'C_PERIDO_LIMIT' ) );
        $builder->set( 'C_PERIOD_START_AT',         _elm( $param, 'C_PERIOD_START_AT' ) );
        $builder->set( 'C_PERIOD_END_AT',           _elm( $param, 'C_PERIOD_END_AT' ) );
        $builder->set( 'C_EXPIRE_MONTH',            _elm( $param, 'C_EXPIRE_MONTH' ) );
        $builder->set( 'C_DUPLICATE_USE_FLAG',      _elm( $param, 'C_DUPLICATE_USE_FLAG' ) );
        $builder->set( 'C_IS_SUPER_FLAG',           _elm( $param, 'C_IS_SUPER_FLAG' ) );
        $builder->set( 'C_CREATE_AT',               _elm( $param, 'C_CREATE_AT' ) );
        $builder->set( 'C_CREATE_IP',               _elm( $param, 'C_CREATE_IP' ) );
        $builder->set( 'C_CREATE_MB_IDX',           _elm( $param, 'C_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;


    }

    public function updateCoupon( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON' );
        $builder->set( 'C_NAME',                    _elm( $param, 'C_NAME' ) );
        $builder->set( 'C_DISCRIPTION',             _elm( $param, 'C_DISCRIPTION' ) );
        $builder->set( 'C_PUB_GBN',                 _elm( $param, 'C_PUB_GBN' ) );
        $builder->set( 'C_TARGET',                  _elm( $param, 'C_TARGET' ) );
        $builder->set( 'C_TARGET_GRADE_IDX',        _elm( $param, 'C_TARGET_GRADE_IDX' ) );
        $builder->set( 'C_ISSUE_NO_LIMIT_FLAG',     _elm( $param, 'C_ISSUE_NO_LIMIT_FLAG' ) );
        $builder->set( 'C_ISSUE_LIMIT',             _elm( $param, 'C_ISSUE_LIMIT' ) );
        $builder->set( 'C_NOTI_FLAG',               _elm( $param, 'C_NOTI_FLAG' ) );
        $builder->set( 'C_BENEFIT_GBN',             _elm( $param, 'C_BENEFIT_GBN' ) );
        $builder->set( 'C_BENEFIT',                 _elm( $param, 'C_BENEFIT' ) );
        $builder->set( 'C_BENEFIT_UNIT',            _elm( $param, 'C_BENEFIT_UNIT' ) );
        $builder->set( 'C_MIN_PRICE',               _elm( $param, 'C_MIN_PRICE' ) );
        $builder->set( 'C_STATUS',                  _elm( $param, 'C_STATUS' ) );
        $builder->set( 'C_SCOPE_GBN',               _elm( $param, 'C_SCOPE_GBN' ) );
        $builder->set( 'C_EXCEPT_PRODUCT_IDXS',     _elm( $param, 'C_EXCEPT_PRODUCT_IDXS' ) );
        $builder->set( 'C_PICK_ITEMS',              _elm( $param, 'C_PICK_ITEMS' ) );
        $builder->set( 'C_PERIDO_LIMIT',            _elm( $param, 'C_PERIDO_LIMIT' ) );
        $builder->set( 'C_PERIOD_START_AT',         _elm( $param, 'C_PERIOD_START_AT' ) );
        $builder->set( 'C_PERIOD_END_AT',           _elm( $param, 'C_PERIOD_END_AT' ) );
        $builder->set( 'C_EXPIRE_MONTH',            _elm( $param, 'C_EXPIRE_MONTH' ) );
        $builder->set( 'C_DUPLICATE_USE_FLAG',      _elm( $param, 'C_DUPLICATE_USE_FLAG' ) );
        $builder->set( 'C_IS_SUPER_FLAG',           _elm( $param, 'C_IS_SUPER_FLAG' ) );
        $builder->set( 'C_UPDATE_AT',               _elm( $param, 'C_UPDATE_AT' ) );
        $builder->set( 'C_UPDATE_IP',               _elm( $param, 'C_UPDATE_IP' ) );
        $builder->set( 'C_UPDATE_MB_IDX',           _elm( $param, 'C_UPDATE_MB_IDX' ) );

        $builder->where( 'C_IDX',                   _elm( $param, 'C_IDX' ) );
        $aReturn                                    = $builder->update();

        return $aReturn;
    }


    public function getCouponIssueLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'COUPON_ISSUE CI' );
        $builder->select( 'CI.*' );
        $builder->select( 'MB.MB_NM, MB.MB_USERID' );
        $builder->join( 'MEMBERSHIP MB', 'CI.I_MB_IDX = MB.MB_IDX', 'left' );
        $builder->where( 'CI.I_CPN_IDX',            _elm( $param, 'C_IDX' ) );

        if( !empty( _elm( $param, 'MEMBER_NAME' ) ) ){
            $builder->where( 'MB.MB_NM', _elm( $param, 'MEMBER_NAME' ) );
        }

        if( !empty( _elm( $param, 'MEMBER_ID' ) ) ){
            $builder->where( 'MB.MB_USERID', _elm( $param, 'MEMBER_ID' ) );
        }

        if( !empty( _elm( $param, 'I_STATUS' ) )  ){
            $builder->where( 'CI.I_STATUS', _elm( $param, 'I_STATUS' ) );
        }

        // 총 결과 수
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음
        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getCpnIssueCnt( $cpn_idx )
    {
        $aReturn                                    = [];
        if( empty( $cpn_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->where( 'I_CPN_IDX',               $cpn_idx );

        return $builder->countAllResults(false);
    }

    public function checkCpnCode($couponCode)
    {
        $aReturn                                    = false;
        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->where( 'I_COUPON_CODE',           $couponCode );
        $aReturn                                    = $builder->countAllResults() > 0;
        return $aReturn;
    }

    public function insertCpnIssue( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->set( 'I_COUPON_CODE',             _elm( $param, 'I_COUPON_CODE' ) );
        $builder->set( 'I_CPN_IDX',                 _elm( $param, 'I_CPN_IDX' ) );
        $builder->set( 'I_START_AT',                _elm( $param, 'I_START_AT' ) );
        $builder->set( 'I_END_AT',                  _elm( $param, 'I_END_AT' ) );
        $builder->set( 'I_ISSUE_AGENT',             _elm( $param, 'I_ISSUE_AGENT' ) );
        $builder->set( 'I_STATUS',                  _elm( $param, 'I_STATUS' ) );
        $builder->set( 'I_ISSUE_GBN',               _elm( $param, 'I_ISSUE_GBN' ) );
        $builder->set( 'I_ISSUE_AT',                _elm( $param, 'I_ISSUE_AT' ) );
        $builder->set( 'I_ISSUE_IP',                _elm( $param, 'I_ISSUE_IP' ) );
        $builder->set( 'I_ISSUE_MB_IDX',            _elm( $param, 'I_ISSUE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }

        return $aReturn;
    }

    public function getIsseuDataByidx( $issue_idx )
    {
        $aReturn                                    = [];
        if( empty( $issue_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'COUPON_ISSUE CI' );

        $builder->select( 'CI.*' );
        $builder->select( 'CP.C_IDX,CP.C_NAME,CP.C_PUB_GBN,CP.C_STATUS,CP.C_SCOPE_GBN');
        $builder->select( 'CP.C_IS_SUPER_FLAG,CP.C_PERIDO_LIMIT,CP.C_PERIOD_START_AT,CP.C_PERIOD_END_AT' );
        $builder->select( 'CP.C_TARGET,C_TARGET_GRADE_IDX' );
        $builder->join( 'COUPON CP', 'CI.I_CPN_IDX = CP.C_IDX', 'left' );
        $builder->where( 'CI.I_IDX',                $issue_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }
    public function couponJoinUser( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->set( 'I_MB_IDX',                  _elm( $param, 'I_MB_IDX' ) );
        $builder->where( 'I_IDX',                   _elm( $param, 'I_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function deleteIssueDataByIdx( $issue_idx )
    {
        $aReturn                                    = false;
        if( empty( $issue_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->where( 'I_IDX',                   $issue_idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function getUserCpnCount( $mb_idx )
    {
        $aReturn                                    = false;
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'COUPON_ISSUE' );
        $builder->where( 'I_MB_IDX',                $mb_idx );

        return $builder->countAllResults(false);

    }



    public function getUserCouponAndIssueLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'COUPON_ISSUE CI' );
        $builder->select( 'CI.*' );
        $builder->select( 'CP.*' );
        $builder->select( 'MB.MB_NM, MB.MB_USERID' );
        $builder->join( 'COUPON CP', 'CI.I_CPN_IDX=CP.C_IDX', 'inner' );
        $builder->join( 'MEMBERSHIP MB', 'CI.I_MB_IDX = MB.MB_IDX', 'left' );

        if( !empty( _elm( $param, 'M_MB_IDX' ) ) ){
            $builder->where( 'CI.I_MB_IDX',         _elm( $param, 'M_MB_IDX' ) );
        }

        if( empty( _elm( $param, 's_start_date' ) ) === false && empty( _elm( $param, 's_end_date' ) ) === false ){
            $builder->where( 'CI.I_ISSUE_AT >=',    _elm( $param, 's_start_date' ) );
            $builder->where( 'CI.I_ISSUE_AT <=',    _elm( $param, 's_end_date' ) );
        }
        if( empty( _elm( $param, 's_status' ) ) === false ){
            if( _elm( $param, 's_status' ) == 'usePassible' ){
                $builder->where( 'CP.C_STATUS',     'Y' );
                $builder->where( 'CI.I_STATUS',     'N' );
            }else if( _elm( $param, 's_status' ) == 'used' ){
                $builder->where( 'CP.C_STATUS',     'Y' );
                $builder->where( 'CI.I_STATUS',     'Y' );
            }else{
                $builder->where( 'CP.C_STATUS!=',   'Y' );
            }

        }

        // 총 결과 수
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음
        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }
        return $aReturn;

    }
}