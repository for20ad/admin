<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class BundleModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    #------------------------------------------------------------------
    # TODO: list START
    #------------------------------------------------------------------

    public function getDefaultGoodsLists()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_G_DEFAULT' );
        $builder->orderBy( 'A_SORT',                'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getBestGoodsLists()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_G_BEST' );
        $builder->orderBy( 'A_SORT',                'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getNewGoodsLists()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_G_NEW' );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getTimeSaleLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE T' );
        $builder->select( 'T.*' );
        $builder->select( 'IFNULL(TD.CNT, 0) as GOODS_CNT' );
        $builder->join( '( SELECT COUNT( AD_IDX ) CNT, AD_P_IDX  FROM GOODS_G_TIMESALE_DETAIL WHERE 1=1 GROUP BY AD_P_IDX ) TD', 'TD.AD_P_IDX = T.A_IDX', 'left' );
        if( empty( _elm( $param, 'A_TITLE' ) ) === false ){
            $builder->like( 'T.A_TITLE', _elm( $param, 'A_TITLE' ), 'both' );
        }

        if( empty( _elm( $param, 'START_DATE' ) ) === false ){
            $builder->where( 'T.A_PERIOD_START_AT <=', _elm( $param, 'START_DATE' ) );
        }
        if( empty( _elm( $param, 'END_DATE' ) ) === false ){
            $builder->where( 'T.A_PERIOD_END_AT >=', _elm( $param, 'END_DATE' ) );
        }
        if( empty( _elm( $param, 'A_OPEN_STATUS' ) ) === false ){
            $builder->where( 'T.A_OPEN_STATUS', _elm( $param, 'A_OPEN_STATUS' ) );
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

    public function getWeeklyGoodsLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY T' );
        $builder->select( 'T.*' );
        $builder->select( 'IFNULL(TD.CNT, 0) as GOODS_CNT' );
        $builder->join( '( SELECT COUNT( AD_IDX ) CNT, AD_P_IDX  FROM GOODS_G_WEEKLY_DETAIL WHERE 1=1 GROUP BY AD_P_IDX ) TD', 'TD.AD_P_IDX = T.A_IDX', 'left' );
        if( empty( _elm( $param, 'A_TITLE' ) ) === false ){
            $builder->like( 'T.A_TITLE', _elm( $param, 'A_TITLE' ), 'both' );
        }

        if( empty( _elm( $param, 'START_DATE' ) ) === false ){
            $builder->where( 'T.A_PERIOD_START_AT <=', _elm( $param, 'START_DATE' ) );
        }
        if( empty( _elm( $param, 'END_DATE' ) ) === false ){
            $builder->where( 'T.A_PERIOD_END_AT >=', _elm( $param, 'END_DATE' ) );
        }
        if( empty( _elm( $param, 'A_OPEN_STATUS' ) ) === false ){
            $builder->where( 'T.A_OPEN_STATUS', _elm( $param, 'A_OPEN_STATUS' ) );
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

    public function timeSaleDiffTimes( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->groupStart();
        $builder->where( 'UNIX_TIMESTAMP(A_PERIOD_START_AT) <=',    strtotime( _elm( $param, 'A_PERIOD_END_AT' ) ) );
        $builder->where( 'UNIX_TIMESTAMP(A_PERIOD_END_AT) >=',      strtotime( _elm( $param, 'A_PERIOD_START_AT' ) ) );
        $builder->groupEnd();
        $overlapCount                               = $builder->countAllResults();

        if($overlapCount < 1){
            $aReturn                                = true;
        }
        return $aReturn;
    }

    public function weeklyGoodsDiffTimes( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->groupStart();
        $builder->where( 'UNIX_TIMESTAMP(A_PERIOD_START_AT) <=',    strtotime( _elm( $param, 'A_PERIOD_END_AT' ) ) );
        $builder->where( 'UNIX_TIMESTAMP(A_PERIOD_END_AT) >=',      strtotime( _elm( $param, 'A_PERIOD_START_AT' ) ) );
        $builder->groupEnd();
        $overlapCount                               = $builder->countAllResults();

        if($overlapCount < 1){
            $aReturn                                = true;
        }
        return $aReturn;
    }


    public function timeSalseDataByIdx( $a_idx )
    {
        $aReturn                                    = [];
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE' );
        $builder->where( 'A_IDX',                   $a_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function weeklyGoodsDataByIdx( $a_idx )
    {
        $aReturn                                    = [];
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->where( 'A_IDX',                   $a_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getTimeSaleGoodsLists( $a_idx )
    {
        $aReturn                                    = [];
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE_DETAIL' );
        $builder->where( 'AD_P_IDX',                $a_idx );
        $builder->orderBy( 'AD_SORT',               'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function getWeeklyGoodsDetailLists( $a_idx )
    {
        $aReturn                                    = [];
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY_DETAIL' );
        $builder->where( 'AD_P_IDX',                $a_idx );
        $builder->orderBy( 'AD_SORT',               'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    #------------------------------------------------------------------
    # TODO: list END
    #------------------------------------------------------------------

    #------------------------------------------------------------------
    # TODO: delete START
    #------------------------------------------------------------------
    public function deleteDefaultGoods( $idxs )
    {
        $aReturn                                    = false;

        if( empty( $idxs ) ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_DEFAULT' );
        $builder->whereIn( 'A_GOODS_IDX',           $idxs );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }
    public function deleteBestGoods( $idxs )
    {
        $aReturn                                    = false;

        if( empty( $idxs ) ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_BEST' );
        $builder->whereIn( 'A_GOODS_IDX',           $idxs );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function deleteNewGoods( $idxs )
    {
        $aReturn                                    = false;

        if( empty( $idxs ) ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_NEW' );
        $builder->whereIn( 'A_GOODS_IDX',           $idxs );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function deleteTimeSale( $idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE' );
        $builder->where( 'A_IDX',                   $idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }
    public function deleteTimeSaleDetail( $idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE_DETAIL' );
        $builder->where( 'AD_P_IDX',                $idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }


    public function deleteTimeSaleDetailGoods( $p_idx, $goods_idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE_DETAIL' );
        $builder->whereIn( 'AD_GOODS_IDX',          $goods_idx);
        $builder->where( 'AD_P_IDX',                $p_idx );

        $aReturn                                    = $builder->delete();

        return $aReturn;

    }
    public function deleteWeeklyGoodsDetailGoods( $p_idx, $goods_idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY_DETAIL' );
        $builder->whereIn( 'AD_GOODS_IDX',          $goods_idx);
        $builder->where( 'AD_P_IDX',                $p_idx );

        $aReturn                                    = $builder->delete();

        return $aReturn;

    }
    public function deleteWeeklyGoods( $idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->where( 'A_IDX',                   $idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }
    public function deleteWeeklyGoodsDetail( $idx )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY_DETAIL' );
        $builder->where( 'AD_P_IDX',                $idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }

    #------------------------------------------------------------------
    # TODO: delete END
    #------------------------------------------------------------------

    #------------------------------------------------------------------
    # TODO: update START
    #------------------------------------------------------------------
    public function updateDefaultGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_DEFAULT' );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_UPDATE_AT',               _elm( $param, 'A_UPDATE_AT' ) );
        $builder->set( 'A_UPDATE_IP',               _elm( $param, 'A_UPDATE_IP' ) );
        $builder->set( 'A_UPDATE_MB_IDX',           _elm( $param, 'A_UPDATE_MB_IDX' ) );
        $builder->where( 'A_GOODS_IDX',             _elm( $param, 'A_GOODS_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function updateBestGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_BEST' );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_UPDATE_AT',               _elm( $param, 'A_UPDATE_AT' ) );
        $builder->set( 'A_UPDATE_IP',               _elm( $param, 'A_UPDATE_IP' ) );
        $builder->set( 'A_UPDATE_MB_IDX',           _elm( $param, 'A_UPDATE_MB_IDX' ) );
        $builder->where( 'A_GOODS_IDX',             _elm( $param, 'A_GOODS_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function updateNewGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_NEW' );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_UPDATE_AT',               _elm( $param, 'A_UPDATE_AT' ) );
        $builder->set( 'A_UPDATE_IP',               _elm( $param, 'A_UPDATE_IP' ) );
        $builder->set( 'A_UPDATE_MB_IDX',           _elm( $param, 'A_UPDATE_MB_IDX' ) );
        $builder->where( 'A_GOODS_IDX',             _elm( $param, 'A_GOODS_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }


    public function updateTimeSale( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE' );
        $builder->set( 'A_TITLE',                   _elm( $param, 'A_TITLE' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_PERIOD_START_AT',         _elm( $param, 'A_PERIOD_START_AT' ) );
        $builder->set( 'A_PERIOD_END_AT',           _elm( $param, 'A_PERIOD_END_AT' ) );
        $builder->set( 'A_OPEN_STATUS',             _elm( $param, 'A_OPEN_STATUS' ) );
        $builder->set( 'A_UPDATE_AT',               _elm( $param, 'A_UPDATE_AT' ) );
        $builder->set( 'A_UPDATE_IP',               _elm( $param, 'A_UPDATE_IP' ) );
        $builder->set( 'A_UPDATE_MB_IDX',           _elm( $param, 'A_UPDATE_MB_IDX' ) );
        $builder->where( 'A_IDX',                   _elm( $param, 'A_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function updateWeeklyGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->set( 'A_TITLE',                   _elm( $param, 'A_TITLE' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_PERIOD_START_AT',         _elm( $param, 'A_PERIOD_START_AT' ) );
        $builder->set( 'A_PERIOD_END_AT',           _elm( $param, 'A_PERIOD_END_AT' ) );
        $builder->set( 'A_OPEN_STATUS',             _elm( $param, 'A_OPEN_STATUS' ) );
        $builder->set( 'A_UPDATE_AT',               _elm( $param, 'A_UPDATE_AT' ) );
        $builder->set( 'A_UPDATE_IP',               _elm( $param, 'A_UPDATE_IP' ) );
        $builder->set( 'A_UPDATE_MB_IDX',           _elm( $param, 'A_UPDATE_MB_IDX' ) );
        $builder->where( 'A_IDX',                   _elm( $param, 'A_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;

    }
    public function updateTimeSaleDetailGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE_DETAIL' );
        $builder->set( 'AD_GOODS_IDX',              _elm( $param, 'AD_GOODS_IDX' ) );
        $builder->set( 'AD_SORT',                   _elm( $param, 'AD_SORT' ) );
        $builder->set( 'AD_UPDATE_AT',              _elm( $param, 'AD_UPDATE_AT' ) );
        $builder->set( 'AD_UPDATE_IP',              _elm( $param, 'AD_UPDATE_IP' ) );
        $builder->set( 'AD_UPDATE_MB_IDX',          _elm( $param, 'AD_UPDATE_MB_IDX' ) );
        $builder->where( 'AD_P_IDX',                _elm( $param, 'AD_P_IDX' ) );
        $builder->where( 'AD_GOODS_IDX',            _elm( $param, 'AD_GOODS_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function updateWeeklyGoodsDetailGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY_DETAIL' );
        $builder->set( 'AD_GOODS_IDX',              _elm( $param, 'AD_GOODS_IDX' ) );
        $builder->set( 'AD_SORT',                   _elm( $param, 'AD_SORT' ) );
        $builder->set( 'AD_UPDATE_AT',              _elm( $param, 'AD_UPDATE_AT' ) );
        $builder->set( 'AD_UPDATE_IP',              _elm( $param, 'AD_UPDATE_IP' ) );
        $builder->set( 'AD_UPDATE_MB_IDX',          _elm( $param, 'AD_UPDATE_MB_IDX' ) );
        $builder->where( 'AD_P_IDX',                _elm( $param, 'AD_P_IDX' ) );
        $builder->where( 'AD_GOODS_IDX',            _elm( $param, 'AD_GOODS_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    #------------------------------------------------------------------
    # TODO: update END
    #------------------------------------------------------------------

    #------------------------------------------------------------------
    # TODO: insert START
    #------------------------------------------------------------------
    public function insertDefaultGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_DEFAULT' );
        $builder->set( 'A_GOODS_IDX',               _elm( $param, 'A_GOODS_IDX' ) );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_CREATE_AT',               _elm( $param, 'A_CREATE_AT' ) );
        $builder->set( 'A_CREATE_IP',               _elm( $param, 'A_CREATE_IP' ) );
        $builder->set( 'A_CREATE_MB_IDX',           _elm( $param, 'A_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function insertBestGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_BEST' );
        $builder->set( 'A_GOODS_IDX',               _elm( $param, 'A_GOODS_IDX' ) );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_CREATE_AT',               _elm( $param, 'A_CREATE_AT' ) );
        $builder->set( 'A_CREATE_IP',               _elm( $param, 'A_CREATE_IP' ) );
        $builder->set( 'A_CREATE_MB_IDX',           _elm( $param, 'A_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function insertNewGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_NEW' );
        $builder->set( 'A_GOODS_IDX',               _elm( $param, 'A_GOODS_IDX' ) );
        $builder->set( 'A_SORT',                    _elm( $param, 'A_SORT' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_CREATE_AT',               _elm( $param, 'A_CREATE_AT' ) );
        $builder->set( 'A_CREATE_IP',               _elm( $param, 'A_CREATE_IP' ) );
        $builder->set( 'A_CREATE_MB_IDX',           _elm( $param, 'A_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function insertTimeSale( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE' );
        $builder->set( 'A_TITLE',                   _elm( $param, 'A_TITLE' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_PERIOD_START_AT',         _elm( $param, 'A_PERIOD_START_AT' ) );
        $builder->set( 'A_PERIOD_END_AT',           _elm( $param, 'A_PERIOD_END_AT' ) );
        $builder->set( 'A_OPEN_STATUS',             _elm( $param, 'A_OPEN_STATUS' ) );
        $builder->set( 'A_CREATE_AT',               _elm( $param, 'A_CREATE_AT' ) );
        $builder->set( 'A_CREATE_IP',               _elm( $param, 'A_CREATE_IP' ) );
        $builder->set( 'A_CREATE_MB_IDX',           _elm( $param, 'A_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }

    public function insertWeeklyGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY' );
        $builder->set( 'A_TITLE',                   _elm( $param, 'A_TITLE' ) );
        $builder->set( 'A_LIMIT',                   _elm( $param, 'A_LIMIT' ) );
        $builder->set( 'A_PERIOD_START_AT',         _elm( $param, 'A_PERIOD_START_AT' ) );
        $builder->set( 'A_PERIOD_END_AT',           _elm( $param, 'A_PERIOD_END_AT' ) );
        $builder->set( 'A_OPEN_STATUS',             _elm( $param, 'A_OPEN_STATUS' ) );
        $builder->set( 'A_CREATE_AT',               _elm( $param, 'A_CREATE_AT' ) );
        $builder->set( 'A_CREATE_IP',               _elm( $param, 'A_CREATE_IP' ) );
        $builder->set( 'A_CREATE_MB_IDX',           _elm( $param, 'A_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }

    public function insertTimeSaleDetailGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_TIMESALE_DETAIL' );
        $builder->set( 'AD_GOODS_IDX',              _elm( $param, 'AD_GOODS_IDX' ) );
        $builder->set( 'AD_SORT',                   _elm( $param, 'AD_SORT' ) );
        $builder->set( 'AD_CREATE_AT',              _elm( $param, 'AD_CREATE_AT' ) );
        $builder->set( 'AD_CREATE_IP',              _elm( $param, 'AD_CREATE_IP' ) );
        $builder->set( 'AD_CREATE_MB_IDX',          _elm( $param, 'AD_CREATE_MB_IDX' ) );
        $builder->set( 'AD_P_IDX',                  _elm( $param, 'AD_P_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function insertWeeklyGoodsDetailGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_G_WEEKLY_DETAIL' );
        $builder->set( 'AD_GOODS_IDX',              _elm( $param, 'AD_GOODS_IDX' ) );
        $builder->set( 'AD_SORT',                   _elm( $param, 'AD_SORT' ) );
        $builder->set( 'AD_CREATE_AT',              _elm( $param, 'AD_CREATE_AT' ) );
        $builder->set( 'AD_CREATE_IP',              _elm( $param, 'AD_CREATE_IP' ) );
        $builder->set( 'AD_CREATE_MB_IDX',          _elm( $param, 'AD_CREATE_MB_IDX' ) );
        $builder->set( 'AD_P_IDX',                  _elm( $param, 'AD_P_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    #------------------------------------------------------------------
    # TODO: insert END
    #------------------------------------------------------------------
}