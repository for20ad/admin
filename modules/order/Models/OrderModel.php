<?php
namespace Module\order\Models;

use Config\Services;
use CodeIgniter\Model;

class OrderModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function updateShipInfo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->set( 'O_SHIP_NUMBER',             _elm( $param, 'O_SHIP_NUMBER' ) );
        $builder->set( 'O_SHIP_COMPANY',            _elm( $param, 'O_SHIP_COMPANY' ) );
        $builder->where( 'O_IDX',                   _elm( $param, 'O_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function updateShipTracking( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->where( 'O_IDX',                   _elm( $param, 'O_IDX' ) );
        $builder->set( 'O_SHIP_TRACKING',           _elm( $param, 'O_SHIP_TRACKING' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function getDeliveryCompay()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        $builder->where( 'D_USE_YN',                'Y' );
        $builder->orderBy( 'D_IDX',                 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getOrderInfoLists( $param = [] )
    {
        $aReturn =                                  [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        if (empty($param)) {
            return $aReturn;
        }

        $builder =                                  $this->db->table('ORDER_INFO OI');
        $builder->select('OI.*');
        $builder->select( 'OG.P_NAME, OG.P_OPTION_NAME' );
        $builder->select( 'OO.O_ORDID as ORDER_ID, OO.O_IDX as ORDER_IDX, OO.O_MB_IDX, OO.O_PAY_METHOD' );
        $builder->select( 'OO.O_TOTAL_PRICE, OO.O_PG_PRICE, OO.O_SHIP_PRICE, OO.O_ADD_SHIP_PRICE, OO.O_CPN_MINUS_PRICE, OO.O_CPN_PLUS_MINUS_PRICE' );

        $builder->select( 'MB.MB_NM' );

        $builder->join('ORDER_GOODS OG', 'OI.O_IDX = OG.P_O_IDX', 'left');
        $builder->join( 'ORDER_ORIGIN OO', 'OI.O_ORDID=OO.O_ORDID', 'left' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=OO.O_MB_IDX', 'left' );


        // ORDER_INFO의 O_STATUS 조건
        if (!empty(_elm($param, 'O_STATUS'))) {
            $status = _elm($param, 'O_STATUS');
            if ( is_array($status) ) {

                $builder->whereIn('OI.O_STATUS', array_values($status) );
            } else {
                $builder->where( 'OI.O_STATUS', _elm( $param, 'O_STATUS' ) );
            }
        }

        if (!empty(_elm($param, 'ORDID'))) {
            $builder->where('OI.O_ORDID',           _elm($param, 'ORDID') );
        }

        if( !empty( _elm( $param, 'MB_USERID' ) ) ){
            $builder->like( 'MB.MB_USERID',         _elm( $param, 'MB_USERID' ), 'both' );
        }
        if( !empty( _elm( $param, 'MB_NM' ) ) ){
            $builder->like( 'MB.MB_NM',             _elm( $param, 'MB_NM' ), 'both'  );
        }
        if( !empty( _elm( $param, 'MB_MOBILE_NUM' ) ) ){
            $builder->where( 'MB.MB_MOBILE_NUM',    _elm( $param, 'MB_MOBILE_NUM' ) );
        }


        if( empty( _elm( $param, 'O_PAY_METHOD' ) ) === false ){
            $builder->where( 'OO.O_PAY_METHOD',     _elm( $param, 'O_PAY_METHOD' ) );
        }

        if (!empty(_elm($param, 'START_DATE')) && !empty(_elm($param, 'END_DATE'))) {
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') >=', _elm($param, 'START_DATE'));
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') <=', _elm($param, 'END_DATE'));
        }



        $aReturn['total_count'] =                   $builder->countAllResults(false);

        // 정렬
        if (!empty(_elm($param, 'order'))) {
            $builder->orderBy(_elm($param, 'order'));
        }

        // 페이징 처리
        if (!empty(_elm($param, 'limit'))) {
            $builder->limit((int)_elm($param, 'limit'), (int)(_elm($param, 'start') ?? 0));
        }

        $query =                                    $builder->get();

        //var_dump( $this->db->getLastQuery() ) ;
        if ($this->db->affectedRows()) {
            $results =                              $query->getResultArray();

            $aReturn['lists'] = $results;
        }

        return $aReturn;
    }

    public function orderStatusChangeByOrdId( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->set( 'O_CHANGE_BEFORE_STATUS',    'O_STATUS', false);
        $builder->where( 'O_ORDID',                 _elm( $param, 'O_ORDID' ) );
        $builder->set( 'O_STATUS',                  _elm( $param, 'O_STATUS' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function orderStatusChangeMultiple( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->whereIn( 'O_IDX',                 _elm( $param, 'O_IDXS' ) );
        $builder->set( 'O_STATUS',                  _elm( $param, 'O_STATUS' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getOrderDetail( $ordIdx )
    {
        $aReturn                                    = [];
        if( empty( $ordIdx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_ORIGIN' );
        $builder->where( 'O_IDX',                   $ordIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getOrderDataByIdx( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_INFO' );

        $builder->select('*');
        $builder->select('ROW_NUMBER() OVER (ORDER BY O_IDX ASC) AS row_num');

        $builder->where( 'O_IDX',                  _elm( $param, 'O_IDX' ) );
        $builder->orderBy( 'O_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;


    }

    public function getOrderDataByOrdId( $odrId )
    {
        $aReturn                                    = [];
        if( empty( $odrId ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_INFO' );

        $builder->select('*');
        $builder->select('ROW_NUMBER() OVER (ORDER BY O_IDX ASC) AS row_num');

        $builder->where( 'O_ORDID',                  $odrId );
        $builder->orderBy( 'O_IDX', 'ASC' );
        $query                                      = $builder->get();


        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getOrderDataByIdxArr( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_INFO' );

        $builder->select('*');
        $builder->select('ROW_NUMBER() OVER (ORDER BY O_IDX ASC) AS row_num');

        $builder->whereIn( 'O_IDX',                 _elm( $param, 'O_IDXS' ) );
        $builder->orderBy( 'O_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;


    }


    public function getOrderGoodsByParentOdrIdx( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_GOODS' );
        $builder->where( 'P_O_IDX',                 $p_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function deleteMEmo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_MEMO' );
        $builder->where( 'M_IDX',                   _elm( $param, 'M_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;

    }

    public function getMemoDataByIdx( $m_idx )
    {
        $aReturn                                    = [];
        if( empty( $m_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_MEMO' );
        $builder->where( 'M_IDX',                   $m_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }


    public function getMemoLists( $o_id )
    {
        $aReturn                                    = [];
        if( empty( $o_id ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_MEMO M' );
        $builder->select( 'M.*' );
        $builder->select('DATE(M.M_CREATE_AT) as memo_date');
        $builder->select( 'ADM.MB_USERNAME M_WRITER_NAME' );
        $builder->join( 'ADMIN_MEMBER ADM', 'ADM.MB_IDX=M.M_CREATE_MB_IDX', 'left' );
        $builder->where( 'M.M_ORD_ID',              $o_id );
        $builder->orderBy('M.M_CREATE_AT', 'DESC');
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getLastMemo( $o_id )
    {
        $aReturn                                    = [];
        if( empty( $o_id ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_MEMO M' );
        $builder->select( 'M.*' );
        $builder->select( 'ADM.MB_USERNAME M_WRITER_NAME' );
        $builder->join( 'ADMIN_MEMBER ADM', 'ADM.MB_IDX=M_CREATE_MB_IDX', 'left' );
        $builder->where( 'M.M_ORD_ID',             $o_id );

        $builder->orderBy( 'M.M_CREATE_AT', 'DESC' );

        $builder->limit(1);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }

        return $aReturn;

    }
    public function getMemoCnt( $o_id )
    {
        $aReturn                                    = [];
        if( empty( $o_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_MEMO M' );
        $builder->where( 'M.M_ORD_ID',              $o_id );

        return $builder->countAllResults(false);
    }

    public function changeMemoStatus( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_MEMO' );
        $builder->where( 'M_IDX',                   _elm( $param, 'M_IDX' ) );

        $builder->set( 'M_STATUS',                  _elm( $param, 'M_STATUS' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }


    public function insertMemo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_MEMO' );
        $builder->set( 'M_ORD_ID',                  _elm( $param, 'M_ORD_ID' ) );
        $builder->set( 'M_CONTENT',                 _elm( $param, 'M_CONTENT' ) );
        $builder->set( 'M_STATUS',                  _elm( $param, 'M_STATUS' ) );
        $builder->set( 'M_CREATE_AT',               _elm( $param, 'M_CREATE_AT' ) );
        $builder->set( 'M_CREATE_IP',               _elm( $param, 'M_CREATE_IP' ) );
        $builder->set( 'M_CREATE_MB_IDX',           _elm( $param, 'M_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
    public function getOrderOriginByOrdIdx( $ordIdx )
    {
        $aReturn                                    = [];
        if( empty( $ordIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_ORIGIN' );
        $builder->where( 'O_IDX',                   $ordIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }
    public function getOrderOriginByOrdID( $ordID )
    {
        $aReturn                                    = [];
        if( empty( $ordID ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_ORIGIN' );
        $builder->where( 'O_ORDID',                 $ordID );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }
    public function getOrderInfoByOrdID( $ordId )
    {
        $aReturn                                    = [];
        if( empty( $ordId ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->where( 'O_ORDID',                 $ordId );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function getOrderInfoByOrdIdx( $ordIdx )
    {
        $aReturn                                    = [];
        if( empty( $ordIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_INFO' );
        $builder->where( 'O_IDX',                   $ordIdx );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }
    public function getOrderLists( $param = [] )
    {
        $aReturn =                                  [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        if (empty($param)) {
            return $aReturn;
        }

        $builder =                                  $this->db->table('ORDER_ORIGIN OO');
        $builder->select('OO.O_IDX, OO.O_ORDER_AT, OO.O_ORDID, OO.O_TITLE, OO.O_TOTAL_PRICE, OO.O_PG_PRICE, OO.O_STATUS, OO.O_PAY_METHOD, OO.O_DEVICE, OO.O_PG_BANK_CODE, OO.O_PG_ACCOUNT_NUM, OO.O_SAVE_MILEAGE');
        $builder->select('OO.O_SHIP_PRICE, OO.O_ADD_SHIP_PRICE, OO.O_CPN_MINUS_PRICE, OO.O_CPN_PLUS_MINUS_PRICE, OO.O_USE_MILEAGE');

        $builder->select('GROUP_CONCAT(OG.P_NUM ORDER BY OG.P_PRID SEPARATOR \',\') as P_QTYS');
        $builder->select( 'MB.MB_USERID, MB.MB_NM, MG.G_NAME' );
        $builder->select( 'OI.STATUSES' );
        // 첫 주문 날짜를 서브쿼리로 가져오기
        $subQuery =                                 $this->db->table('ORDER_ORIGIN')
                                                    ->select('MIN(O_IDX)')
                                                    ->where('O_MB_IDX', 'OO.O_MB_IDX', false)
                                                    ->where('O_STATUS', 'BuyDecision')
                                                    ->getCompiledSelect();

        $builder->select("($subQuery) AS FIRST_ORDER_IDX");
        $builder->join('ORDER_GOODS OG', 'OO.O_ORDID = OG.P_ORDID', 'left');
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=OO.O_MB_IDX', 'left' );
        $builder->join( '(SELECT O_ORDID, GROUP_CONCAT(O_STATUS ORDER BY O_STATUS SEPARATOR \',\') AS STATUSES FROM ORDER_INFO GROUP BY O_ORDID) OI', 'OO.O_ORDID = OI.O_ORDID', 'left' );
        $builder->join( 'MEMBER_GRADE MG', 'MB.MB_GRADE_IDX = MG.G_IDX', 'left' );

        // ORDER_INFO의 O_STATUS 조건
        if (!empty(_elm($param, 'O_STATUS'))) {
            $status = _elm($param, 'O_STATUS');
            if (is_array($status)) {
                // O_STATUS가 배열인 경우
                $havingCondition = implode(' OR ', array_map(function ($value) {
                    return "FIND_IN_SET('$value', OI.STATUSES) > 0";
                }, $status));
                $builder->having("($havingCondition)");
            } else {
                // O_STATUS가 단일 값인 경우
                $builder->having("FIND_IN_SET('$status', OI.STATUSES) > 0");
            }
        }
        $builder->where( '1=1');

        if (!empty(_elm($param, 'ORDID')) || !empty(_elm($param, 'MB_USERID')) || !empty(_elm($param, 'MB_NM')) || !empty(_elm($param, 'MB_MOBILE_NUM')) || !empty(_elm($param, 'GOODS_NAME')) ) {
            $builder->groupStart();
            if (!empty(_elm($param, 'ORDID'))) {

                $builder->where('OO.O_ORDID',       _elm($param, 'ORDID'));
            }
            if( !empty( _elm( $param, 'MB_USERID' ) ) ){
                $builder->orLike( 'MB.MB_USERID',   _elm( $param, 'MB_USERID' )  );
            }

            if( !empty( _elm( $param, 'GOODS_NAME' ) ) ){
                $splitKeyword                       = explode( ' ', _elm( $param, 'GOODS_NAME' ) );

                foreach( $splitKeyword as $goodsKeyword ){
                    $builder->like( 'OG.P_NAME',    $goodsKeyword );
                }
            }

            if( !empty( _elm( $param, 'MB_NM' ) ) ){
                $builder->orLike( 'MB.MB_NM',             _elm( $param, 'MB_NM' )   );
            }
            if( !empty( _elm( $param, 'MB_MOBILE_NUM' ) ) ){
                $builder->orWhere( 'MB.MB_MOBILE_NUM',    _elm( $param, 'MB_MOBILE_NUM' ) );

            }
            $builder->groupEnd();
        }


        if( empty( _elm( $param, 'O_PAY_METHOD' ) ) === false ){
            $builder->where( 'OO.O_PAY_METHOD',     _elm( $param, 'O_PAY_METHOD' ) );
        }

        if (!empty(_elm($param, 'START_DATE')) && !empty(_elm($param, 'END_DATE'))) {
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') >=', _elm($param, 'START_DATE'));
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') <=', _elm($param, 'END_DATE'));
        }

        // if (!empty(_elm($param, 'O_STATUS'))) {
        //     $builder->where('OO.O_STATUS',          _elm($param, 'O_STATUS'));
        // }
        $builder->groupBy('OO.O_ORDID');

        $aReturn['total_count']                     = $builder->countAllResults(false);

        // 정렬
        if (!empty(_elm($param, 'order'))) {
            $builder->orderBy(_elm($param, 'order'));
        }

        // 페이징 처리
        if (!empty(_elm($param, 'limit'))) {
            $builder->limit((int)_elm($param, 'limit'), (int)(_elm($param, 'start') ?? 0));
        }

        $query =                                    $builder->get();
        // echo $this->db->getLastQuery().PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;

        if ($this->db->affectedRows()) {
            $results =                              $query->getResultArray();

            // 첫 주문인지 확인
            foreach ($results as &$order) {
                $order['IS_FIRST_ORDER'] = $order['O_IDX'] == $order['FIRST_ORDER_IDX'];
            }

            $aReturn['lists'] = $results;
        }
        return $aReturn;
    }

    public function getOrderStatusCnt( $statusParam = [], $param =[] )
    {
        $aReturn                                    = [];

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder =                                  $this->db->table('ORDER_ORIGIN OO');
        $builder->select('OO.O_IDX');
        $builder->select( 'OI.STATUSES' );
        // 첫 주문 날짜를 서브쿼리로 가져오기
        $builder->join( '(SELECT O_ORDID, GROUP_CONCAT(O_STATUS ORDER BY O_STATUS SEPARATOR \',\') AS STATUSES FROM ORDER_INFO GROUP BY O_ORDID) OI', 'OO.O_ORDID = OI.O_ORDID', 'left' );

       // ORDER_INFO의 O_STATUS 조건
        if (!empty(_elm($statusParam, 'O_STATUS'))) {
            $status = _elm($statusParam, 'O_STATUS');
            if (is_array($status)) {
                // O_STATUS가 배열인 경우
                $havingCondition = implode(' OR ', array_map(function ($value) {
                    return "FIND_IN_SET('$value', OI.STATUSES) > 0";
                }, $status));
                $builder->having("($havingCondition)");
            } else {
                // O_STATUS가 단일 값인 경우
                $builder->having("FIND_IN_SET('$status', OI.STATUSES) > 0");
            }
        }

        if (!empty(_elm($param, 'START_DATE')) && !empty(_elm($param, 'END_DATE'))) {
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') >=', _elm($param, 'START_DATE'));
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') <=', _elm($param, 'END_DATE'));
        }

        $builder->groupBy('OO.O_ORDID');

        $aReturn                                    = $builder->countAllResults(false);
        // echo $this->db->getLastQuery().PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
        return $aReturn;


    }
    public function getOrderClaim( $oInfoIdx )
    {
        $aReturn                                    = [];
        if( empty( $oInfoIdx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_HANDLE' );
        $builder->where( 'H_ORDINFO_IDX',           $oInfoIdx  );
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }

        return $aReturn;
    }
    public function getOrderInfoStatusCnt( $param = [] )
    {
        //print_R( $param );
        $aReturn                                    = [];

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder =                                  $this->db->table('ORDER_INFO OO');
        $builder->select('OO.O_IDX');

        if (!empty(_elm($param, 'O_STATUS'))) {
            foreach( _elm($param, 'O_STATUS') as $o_status ){
                $builder->orWhere( 'O_STATUS',      $o_status );
            }
        }

        $aReturn                                    = $builder->countAllResults(false);
        //echo $this->db->getLastQuery();
        return $aReturn;


    }


    public function getUserOrderDataByOrdNo( $ordNo )
    {
        $aReturn                                    = [];
        if( empty( $ordNo ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table('ORDER_ORIGIN O');
        $builder->select('O.O_IDX, O.O_ORDID, O.O_STATUS, O.O_ORDER_AT, GROUP_CONCAT(OG.P_PRID ORDER BY OG.P_PRID SEPARATOR \',\') as PRODUCT_IDX');
        $builder->select( 'GROUP_CONCAT(OG.P_OPTION_NAME ORDER BY OG.P_PRID SEPARATOR \',\') as OPTION_NAMES ' );
        $builder->select( 'GROUP_CONCAT(OG.P_NUM ORDER BY OG.P_PRID SEPARATOR \',\') as P_QTYS ' );
        $builder->join('ORDER_GOODS OG', 'O.O_ORDID = OG.P_ORDID');
        $builder->join('GOODS G', 'OG.P_PRID = G.G_IDX');
        $builder->where( 'O.O_ORDID',               $ordNo);
        $builder->groupBy('O.O_IDX');

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getSummeryOrderAmt( $mbIdx )
    {
        $aReturn                                    = [];
        if( empty( $mbIdx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ORDER_ORIGIN O' );
        $builder->select( 'SUM( O_TOTAL_PRICE ) ORDER_PRICE, SUM( O_PG_PRICE ) BILL_PRICE' );
        $builder->where( 'O_MB_IDX',                $mbIdx );
        $builder->where( 'O_STATUS',                'BuyDecision' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();

        }
        return $aReturn;
    }

    public function getSummeryOrderProductCount( $mbIdx = [] )
    {
        $aReturn                                    = [];
        if( empty( $mbIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_ORIGIN OO ' );
        $builder->selectSum('OG.P_NUM',             'total_product_count');
        $builder->join( 'ORDER_GOODS OG',           'OO.O_ORDID = OG.P_ORDID' );
        $builder->where( 'OO.O_STATUS',             'BuyDecision' );
        $builder->where( 'OO.O_MB_IDX',             $mbIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;


    }

    public function getLatestOrderListByUserIdx( $mbIdx )
    {
        $aReturn                                    = [];
        if( empty( $mbIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ORDER_ORIGIN' );
        $builder->where( 'O_MB_IDX',                $mbIdx );
        $builder->orderBy( 'O_ORDER_AT', 'DESC' );

        $builder->limit( 5 );


        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getUserOrderLists($param = [])
    {
        $aReturn =                                  [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        if (empty($param)) {
            return $aReturn;
        }

        $builder =                                  $this->db->table('ORDER_ORIGIN OO');
        $builder->select('OO.O_IDX, OO.O_ORDER_AT, OO.O_ORDID, O_TITLE, OO.O_TOTAL_PRICE, OO.O_PG_PRICE, OO.O_STATUS');
        $builder->select('GROUP_CONCAT(OG.P_NUM ORDER BY OG.P_PRID SEPARATOR \',\') as P_QTYS');

        // 첫 주문 날짜를 서브쿼리로 가져오기
        $subQuery =                                 $this->db->table('ORDER_ORIGIN')
                                                    ->select('MIN(O_IDX)')
                                                    ->where('O_MB_IDX', 'OO.O_MB_IDX', false)
                                                    ->where('O_STATUS', 'BuyDecision')
                                                    ->getCompiledSelect();

        $builder->select("($subQuery) AS FIRST_ORDER_DATE");
        $builder->join('ORDER_GOODS OG', 'OO.O_ORDID = OG.P_ORDID', 'left');
        $builder->where('OO.O_MB_IDX',              _elm($param, 'O_MB_IDX'));

        if (!empty(_elm($param, 'O_ORDID'))) {
            $builder->where('OO.O_ORDID',           _elm($param, 'O_ORDID'));
        }

        if (!empty(_elm($param, 'START_DATE')) && !empty(_elm($param, 'END_DATE'))) {
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') >=', _elm($param, 'START_DATE'));
            $builder->where('DATE_FORMAT(OO.O_ORDER_AT, \'%Y-%m-%d\') <=', _elm($param, 'END_DATE'));
        }

        if (!empty(_elm($param, 'O_STATUS'))) {
            $builder->where('OO.O_STATUS',          _elm($param, 'O_STATUS'));
        }
        $builder->groupBy('OO.O_ORDID');

        $aReturn['total_count'] =                   $builder->countAllResults(false);

        // 정렬
        if (!empty(_elm($param, 'order'))) {
            $builder->orderBy(_elm($param, 'order'));
        }

        // 페이징 처리
        if (!empty(_elm($param, 'limit'))) {
            $builder->limit((int)_elm($param, 'limit'), (int)(_elm($param, 'start') ?? 0));
        }

        $query =                                    $builder->get();

        if ($this->db->affectedRows()) {
            $results =                              $query->getResultArray();

            // 첫 주문인지 확인
            foreach ($results as &$order) {
                $order['IS_FIRST_ORDER'] = $order['O_IDX'] == $order['FIRST_ORDER_IDX'];
            }

            $aReturn['lists'] = $results;
        }

        return $aReturn;
    }

}