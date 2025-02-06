<?php
namespace Module\membership\Models;

use Config\Services;
use CodeIgniter\Model;

class MileageModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getUserMileageHistoryLists( $param = [] )
    {
        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];

        if ( empty( $param ) === true ) {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_MILEAGE M' );

        $builder->select( 'M.*' );
        $builder->select( 'C.C_NAME' );
        $builder->select( 'ADM.MB_USERID, ADM.MB_USERNAME' );
        $builder->join( 'CODE C', 'M.M_REASON_CD = C.C_IDX', 'left' );
        $builder->join( 'ADMIN_MEMBER ADM', 'M.M_ADM_IDX = ADM.MB_IDX', 'left' );
        $builder->where( 'M.M_MB_IDX', _elm( $param, 'M_MB_IDX' ) );
        if( empty( _elm( $param, 's_condition' ) ) === false && empty( _elm( $param, 's_keyword' ) ) === false){
            if( _elm( $param, 's_condition' ) == 'orderNo' ){
                $builder->like( 'M.M_USE_HISTORY', _elm( $param, 's_keyword' ), 'both' );
            }
        }
        if( empty( _elm( $param, 's_start_date' ) ) === false && empty( _elm( $param, 's_end_date' ) ) === false ){
            $builder->where( 'M_CREATE_AT >=', _elm( $param, 's_start_date' ) );
            $builder->where( 'M_CREATE_AT <=', _elm( $param, 's_end_date' ) );
        }

        if( empty( _elm( $param, 's_reason_gbn' ) ) === false ){
            $builder->where( 'M.M_REASON_CD', _elm( $param, 's_reason_gbn' ) );
        }
        if( empty( _elm( $param, 's_gbn' ) ) === false ){
            $builder->where( 'M.M_GBN', _elm( $param, 's_gbn' ) );
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

    public function updateMileageSummery( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_MILEAGE_SUMMERY' );
        foreach( $param as $key => $val ){
            if( $key === 'S_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }
        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function updateMileageHistoryData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_MILEAGE' );

        $builder->set( 'M_TYPE',                    _elm( $param, 'M_TYPE' ) );
        $builder->set( 'M_REASON_CD',               _elm( $param, 'M_REASON_CD' ) );
        $builder->set( 'M_REASON_MSG',              _elm( $param, 'M_REASON_MSG' ) );
        $builder->set( 'M_ADM_IDX',                 _elm( $param, 'M_ADM_IDX' ) );

        $builder->where( 'M_IDX',                   _elm( $param, 'M_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;


    }

    public function setMileageHistory( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_MILEAGE' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getUserMileageHistoryDataByIdx( $h_idx )
    {
        $aReturn                                    = [];
        if( empty( $h_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_MILEAGE' );
        $builder->where( 'M_IDX',                   $h_idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getMileageSummeryDataByMbIdx( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $param, 'S_MB_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_MILEAGE_SUMMERY' );
        $builder->where( 'S_MB_IDX', _elm( $param, 'S_MB_IDX' ) );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }
    public function insertUserMileageSummaryData( $param = [] )
    {
        $aReturn                                      = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $param, 'S_MB_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_MILEAGE_SUMMERY' );

        $builder->set( 'S_MB_IDX', _elm( $param, 'S_MB_IDX' ) );
        $builder->set( 'ADD_MILEAGE', _elm( $param, 'ADD_MILEAGE' ) );
        $builder->set( 'USE_MILEAGE', _elm( $param, 'USE_MILEAGE' ) );
        $builder->set( 'DED_MILEAGE', _elm( $param, 'DED_MILEAGE' ) );
        $builder->set( 'EXP_MILEAGE', _elm( $param, 'EXP_MILEAGE' ) );
        $builder->set( 'LAST_UPDATE_AT', _elm( $param, 'LAST_UPDATE_AT' ) );
        $builder->set( 'LAST_UPDATE_IP', _elm( $param, 'LAST_UPDATE_IP' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
}