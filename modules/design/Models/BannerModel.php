<?php
namespace Module\design\Models;

use Config\Services;
use CodeIgniter\Model;

class BannerModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getBannerLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'BANNER' );
        $builder->select('*');


        if ( !empty( $param['B_PERIOD_START_AT'] ) && !empty( $param['B_PERIOD_END_AT'] ) ) {
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' B_PERIOD_START_AT) >=', _elm($param, 'B_PERIOD_START_AT'));
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' B_PERIOD_END_AT) <=', _elm($param, 'B_PERIOD_END_AT'));
        }

        if( !empty( _elm( $param, 'B_VIEW_GBN' ) ) ){
            $builder->where('B_VIEW_GBN', _elm( $param, 'B_VIEW_GBN' ) );
        }
        if( !empty( _elm( $param, 'B_LOCATE' ) ) ){
            $builder->where('B_LOCATE', _elm( $param, 'B_LOCATE' ) );
        }
        if( !empty( _elm( $param, 'B_STATUS' ) ) ){
            $builder->where('B_STATUS', _elm( $param, 'B_STATUS' ) );
        }

        if( !empty( _elm( $param, 'B_TITLE' ) ) ){
            $builder->like('B_TITLE', _elm( $param, 'B_TITLE' ), 'both' );
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

    public function deleteBannerImg( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'BANNER' );
        $builder->set( 'B_IMG_PATH', _elm( $param, 'B_IMB_PATH' ) );
        $builder->where( 'B_IDX', _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function getBannerDataByIdx( $banner_idx )
    {
        $aReturn                                    = [];
        if( empty( $banner_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'BANNER' );
        $builder->where( 'B_IDX', $banner_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }


    public function insertBanner( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'BANNER' );
        $builder->set( 'B_TITLE',                   _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_VIEW_GBN',                _elm( $param, 'B_VIEW_GBN' ) );
        $builder->set( 'B_STATUS',                  _elm( $param, 'B_STATUS' ) );
        $builder->set( 'B_PERIOD_START_AT',         _elm( $param, 'B_PERIOD_START_AT' ) );
        $builder->set( 'B_PERIOD_END_AT',           _elm( $param, 'B_PERIOD_END_AT' ) );
        $builder->set( 'B_LOCATE',                  _elm( $param, 'B_LOCATE' ) );
        $builder->set( 'B_LINK_URL',                _elm( $param, 'B_LINK_URL' ) );
        $builder->set( 'B_OPEN_TARGET',             _elm( $param, 'B_OPEN_TARGET' ) );
        $builder->set( 'B_IMG_PATH',                _elm( $param, 'B_IMG_PATH' ) );
        $builder->set( 'B_CREATE_AT',               _elm( $param, 'B_CREATE_AT' ) );
        $builder->set( 'B_CREATE_IP',               _elm( $param, 'B_CREATE_IP' ) );
        $builder->set( 'B_CREATE_MB_IDX',           _elm( $param, 'B_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }

        return $aReturn;

    }

    public function deleteBanner( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'BANNER' );
        $builder->where( 'B_IDX',                   _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function updateBanner( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'BANNER' );
        $builder->set( 'B_TITLE',                   _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_VIEW_GBN',                _elm( $param, 'B_VIEW_GBN' ) );
        $builder->set( 'B_STATUS',                  _elm( $param, 'B_STATUS' ) );
        $builder->set( 'B_PERIOD_START_AT',         _elm( $param, 'B_PERIOD_START_AT' ) );
        $builder->set( 'B_PERIOD_END_AT',           _elm( $param, 'B_PERIOD_END_AT' ) );
        $builder->set( 'B_LOCATE',                  _elm( $param, 'B_LOCATE' ) );
        $builder->set( 'B_LINK_URL',                _elm( $param, 'B_LINK_URL' ) );
        $builder->set( 'B_OPEN_TARGET',             _elm( $param, 'B_OPEN_TARGET' ) );

        if( empty( _elm( $param, 'B_IMG_PATH' ) ) === false ){
            $builder->set( 'B_IMG_PATH',            _elm( $param, 'B_IMG_PATH' ) );
        }

        $builder->set( 'B_UPDATE_AT',               _elm( $param, 'B_UPDATE_AT' ) );
        $builder->set( 'B_UPDATE_IP',               _elm( $param, 'B_UPDATE_IP' ) );
        $builder->set( 'B_UPDATE_MB_IDX',           _elm( $param, 'B_UPDATE_MB_IDX' ) );

        $builder->where( 'B_IDX',                   _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }
}