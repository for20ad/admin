<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class RequiredInfoModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function geRequiredDetails( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO_DETAIL' );
        $builder->select( 'D_KEY, D_VALUE, D_TYPE' );
        $builder->where( 'D_PARENT_IDX',            $p_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;


    }
    public function insertSubInfos( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO_DETAIL' );
        $builder->set( 'D_PARENT_IDX',              _elm( $param, 'D_PARENT_IDX' ) );
        $builder->set( 'D_KEY',                     _elm( $param, 'D_KEY' ) );
        $builder->set( 'D_VALUE',                   _elm( $param, 'D_VALUE' ) );
        $builder->set( 'D_TYPE',                    _elm( $param, 'D_TYPE' ) );
        $builder->set( 'D_SORT',                    _elm( $param, 'D_SORT' ) );
        $builder->set( 'D_CREATE_AT',               _elm( $param, 'D_CREATE_AT' ) );
        $builder->set( 'D_CREATE_IP',               _elm( $param, 'D_CREATE_IP' ) );
        $builder->set( 'D_CREATE_MB_IDX',           _elm( $param, 'D_CREATE_MB_IDX' ) );

        $aReturn                                    = $builder->insert();

        return $aReturn;
    }

    public function insertInfo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO' );
        $builder->set( 'R_TITLE',                   _elm( $param, 'R_TITLE' ) );
        $builder->set( 'R_CREATE_AT',               _elm( $param, 'R_CREATE_AT' ) );
        $builder->set( 'R_CREATE_IP',               _elm( $param, 'R_CREATE_IP' ) );
        $builder->set( 'R_CREATE_MB_IDX',           _elm( $param, 'R_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getRequiredInfoLists( $param = [] )
    {

        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO' );
        $builder->select('*');

        if( !empty( _elm( $param, 'R_TITLE' ) ) ){
            $builder->where('R_TITLE', _elm( $param, 'R_TITLE' ) );
        }



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


        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;

    }

    public function getRequiredInfoDataByIdx( $info_idx )
    {
        $aReturn                                    = [];
        if( empty( $info_idx ) ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO' );
        $builder->where( 'R_IDX', $info_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }
    public function deleteInfoDetail( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        if( empty( _elm( $param, 'R_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO_DETAIL' );
        $builder->where( 'D_PARENT_IDX',            _elm( $param, 'R_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function getRequiredInfoDataDetail( $info_idx )
    {
        $aReturn                                      = [];
        if( empty( $info_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO_DETAIL' );
        $builder->where( 'D_PARENT_IDX',            $info_idx );
        $builder->orderBy( 'D_SORT',                'ASC' );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function deleteInfo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'R_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO' );
        $builder->where( 'R_IDX',                   _elm( $param, 'R_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function deleteSubInfos( $info_idx )
    {
        $aReturn                                      = false;
        if( empty( $info_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO_DETAIL' );
        $builder->where( 'D_PARENT_IDX',            $info_idx );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function updateInfo( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'R_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_REQUIRED_INFO' );
        $builder->set( 'R_TITLE',                   _elm( $param, 'R_TITLE' ) );
        $builder->set( 'R_UPDATE_AT',               _elm( $param, 'R_UPDATE_AT' ) );
        $builder->set( 'R_UPDATE_IP',               _elm( $param, 'R_UPDATE_IP' ) );
        $builder->set( 'R_UPDATE_MB_IDX',           _elm( $param, 'R_UPDATE_MB_IDX' ) );
        $builder->where( 'R_IDX',                   _elm( $param, 'R_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;

    }


}