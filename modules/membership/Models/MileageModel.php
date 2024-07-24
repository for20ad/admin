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