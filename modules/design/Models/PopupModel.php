<?php
namespace Module\design\Models;

use Config\Services;
use CodeIgniter\Model;

class PopupModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getPopupLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'POPUP' );
        $builder->select('*');


        if ( !empty( $param['P_PERIOD_START_AT'] ) && !empty( $param['P_PERIOD_END_AT'] ) ) {
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' P_PERIOD_START_AT) >=', _elm($param, 'P_PERIOD_START_AT'));
            $builder->where('DATE_FORMAT( \'%Y-%m-%d\' P_PERIOD_END_AT) <=', _elm($param, 'P_PERIOD_END_AT'));
        }

        if( !empty( _elm( $param, 'P_VIEW_GBN' ) ) ){
            $builder->where('P_VIEW_GBN', _elm( $param, 'P_VIEW_GBN' ) );
        }
        if( !empty( _elm( $param, 'P_STATUS' ) ) ){
            $builder->where('P_STATUS', _elm( $param, 'P_STATUS' ) );
        }

        if( !empty( _elm( $param, 'P_TITLE' ) ) ){
            $builder->where('P_TITLE', _elm( $param, 'P_TITLE' ) );
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

    public function getPopupDataByIdx( $pop_idx )
    {
        $aReturn                                    = [];
        if( empty( $pop_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'POPUP' );
        $builder->where( 'P_IDX', $pop_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function insertPopup( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'POPUP' );
        $builder->set( 'P_TITLE',                   _elm( $param, 'P_TITLE' ) );
        $builder->set( 'P_VIEW_GBN',                _elm( $param, 'P_VIEW_GBN' ) );
        $builder->set( 'P_STATUS',                  _elm( $param, 'P_STATUS' ) );
        $builder->set( 'P_PERIOD_START_AT',         _elm( $param, 'P_PERIOD_START_AT' ) );
        $builder->set( 'P_PERIOD_END_AT',           _elm( $param, 'P_PERIOD_END_AT' ) );
        $builder->set( 'P_CONTENT',                 _elm( $param, 'P_CONTENT' ) );
        $builder->set( 'P_LOCATE',                  _elm( $param, 'P_LOCATE' ) );
        $builder->set( 'P_LINK_URL',                _elm( $param, 'P_LINK_URL' ) );
        $builder->set( 'P_WIDTH',                   _elm( $param, 'P_WIDTH' ) );
        $builder->set( 'P_HEIGHT',                  _elm( $param, 'P_HEIGHT' ) );
        $builder->set( 'P_CLOSE_YN',                _elm( $param, 'P_CLOSE_YN' ) );
        $builder->set( 'P_CREATE_AT',               _elm( $param, 'P_CREATE_AT' ) );
        $builder->set( 'P_CREATE_IP',               _elm( $param, 'P_CREATE_IP' ) );
        $builder->set( 'P_CREATE_MB_IDX',           _elm( $param, 'P_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }

        return $aReturn;
    }

    public function updatePopup( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'POPUP' );
        $builder->set( 'P_TITLE',                   _elm( $param, 'P_TITLE' ) );
        $builder->set( 'P_VIEW_GBN',                _elm( $param, 'P_VIEW_GBN' ) );
        $builder->set( 'P_STATUS',                  _elm( $param, 'P_STATUS' ) );
        $builder->set( 'P_PERIOD_START_AT',         _elm( $param, 'P_PERIOD_START_AT' ) );
        $builder->set( 'P_PERIOD_END_AT',           _elm( $param, 'P_PERIOD_END_AT' ) );
        $builder->set( 'P_CONTENT',                 _elm( $param, 'P_CONTENT' ) );
        $builder->set( 'P_LOCATE',                  _elm( $param, 'P_LOCATE' ) );
        $builder->set( 'P_LINK_URL',                _elm( $param, 'P_LINK_URL' ) );
        $builder->set( 'P_WIDTH',                   _elm( $param, 'P_WIDTH' ) );
        $builder->set( 'P_HEIGHT',                  _elm( $param, 'P_HEIGHT' ) );
        $builder->set( 'P_CLOSE_YN',                _elm( $param, 'P_CLOSE_YN' ) );
        $builder->set( 'P_UPDATE_AT',               _elm( $param, 'P_UPDATE_AT' ) );
        $builder->set( 'P_UPDATE_IP',               _elm( $param, 'P_UPDATE_IP' ) );
        $builder->set( 'P_UPDATE_MB_IDX',           _elm( $param, 'P_UPDATE_MB_IDX' ) );

        $builder->where( 'P_IDX', _elm( $param, 'P_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getPopupDatas( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'POPUP' );

        $builder->where( 'P_PERIOD_START_AT <=',   'now()', false );
        $builder->where( 'P_PERIOD_END_AT >=',     'now()', false );
        $builder->where( 'P_STATUS',               'Y' );
        $builder->groupStart();
        $builder->where( 'P_VIEW_GBN',              _elm( $param, 'location' ) );
        $builder->orWhere( 'P_VIEW_GBN',            'ALL' );
        $builder->groupEnd();

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function deletePopup( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'POPUP' );
        $builder->where( 'P_IDX',                   _elm( $param, 'P_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

}