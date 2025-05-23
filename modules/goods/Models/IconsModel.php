<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class IconsModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function getGoodsInIcons( $goods_idx )
    {
        $aReturn                                      = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_IN_ICONS' );
        $builder->where( 'I_GOODS_IDX',             $goods_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;


    }

    public function getIconsLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS_ICONS' );
        $builder->select('*');

        if( !empty( _elm( $param, 'I_NAME' ) ) ){
            $builder->where('I_NAME', _elm( $param, 'I_NAME' ) );
        }
        if ( !empty( $param['START_DATE'] ) && !empty( $param['END_DATE'] ) ) {
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' I_CREATE_AT) >=', _elm($param, 'START_DATE'));
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' I_CREATE_AT) <=', _elm($param, 'END_DATE'));
        }

        if (!empty($param['I_GBN'])) {
            $builder->where('I_GBN', _elm( $param, 'I_GBN' ) );
        }
        if (!empty($param['I_STATUS'])) {
            $builder->where('I_STATUS', _elm( $param, 'I_STATUS' ) );
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

    public function getIconsDataByIdx( $icon_idx )
    {
        $aReturn                                    = [];
        if( empty( $icon_idx ) ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_ICONS' );
        $builder->where( 'I_IDX', $icon_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function deleteIcons( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'I_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_ICONS' );
        $builder->where( 'I_IDX', _elm( $param, 'I_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }




}