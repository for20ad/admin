<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class BrandModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function getBrandCode( $parentIdx = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->select( 'IFNULL( MAX( C_BRAND_CODE ), 0 ) max' );
        $builder->where( 'C_PARENT_IDX',            $parentIdx );
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getSortMax( $parent_idx ){
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->select( 'MAX(C_SORT) max' );
        $builder->where( 'C_PARENT_IDX',            $parent_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getBrandLists( $param = [] )
    {
        $aReturn                                    = [];
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];
        $builder                                    = $this->db->table( 'GOODS_BRAND' );

        $query                                      = $builder->get();

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

    public function getChildBrand( $parentIdx )
    {
        $aReturn                                    = false;
        if( empty( $parentIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->where( 'C_PARENT_IDX',            $parentIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function setBrandSort( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->set( 'C_SORT',                    _elm( $param, 'C_SORT' ) );
        $builder->where( 'C_IDX',                   _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;

    }

    public function deleteBrand( $brand_idx )
    {
        $aReturn                                    = false;
        if(  empty( $brand_idx ) === true){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->where( 'C_IDX',                   $brand_idx );

        $aReturn = $builder->delete();
        return $aReturn;
    }


    public function getParentInfo( $parentIdx = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->select( 'C_BRAND_NAME, C_BRAND_CODE as C_PARENT_CODE, C_IDX as C_PARENT_IDX' );
        $builder->where( 'C_IDX',                   $parentIdx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getBrandDataByIdx( $brand_idx )
    {
        $aReturn                                    = [];
        if( empty( $brand_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_BRAND' );
        $builder->where( 'C_IDX',                   $brand_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

}