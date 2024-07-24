<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class ExcelFormModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }
    public function getFormsDataByMenu( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( _elm( $param, 'F_MENU' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );
        $builder->where( 'F_MENU', _elm( $param, 'F_MENU' ) );
        $builder->orderBy( 'F_SORT', 'ASC' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function deleteForms( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );
        $builder->where( 'F_IDX', _elm( $param, 'F_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;

    }

    public function getFormsDataByIdx( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( _elm( $param, 'F_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );
        $builder->where( 'F_IDX', _elm( $param, 'F_IDX' ) );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function updateFormDatas( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );

        $builder->set( 'F_TITLE', _elm( $param, 'F_TITLE' ) );
        $builder->set( 'F_MENU', _elm( $param, 'F_MENU' ) );
        $builder->set( 'F_LOCATION', _elm( $param, 'F_LOCATION' ) );
        $builder->set( 'F_SORT', _elm( $param, 'F_SORT' ) );
        $builder->set( 'F_FIELDS', _elm( $param, 'F_FIELDS' ) );
        $builder->set( 'F_STATUS', _elm( $param, 'F_STATUS' ) );
        $builder->set( 'F_UPDATE_AT', _elm( $param, 'F_UPDATE_AT' ) );
        $builder->set( 'F_UPDATE_IDX', _elm( $param, 'F_UPDATE_IDX' ) );
        $builder->set( 'F_UPDATE_IP', _elm( $param, 'F_UPDATE_IP' ) );
        $builder->where( 'F_IDX', _elm( $param, 'F_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;

    }
    public function insertFormDatas( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );

        $builder->set( 'F_TITLE', _elm( $param, 'F_TITLE' ) );
        $builder->set( 'F_MENU', _elm( $param, 'F_MENU' ) );
        $builder->set( 'F_LOCATION', _elm( $param, 'F_LOCATION' ) );
        $builder->set( 'F_SORT', _elm( $param, 'F_SORT' ) );
        $builder->set( 'F_FIELDS', _elm( $param, 'F_FIELDS' ) );
        $builder->set( 'F_STATUS', _elm( $param, 'F_STATUS' ) );
        $builder->set( 'F_CREATE_AT', _elm( $param, 'F_CREATE_AT' ) );
        $builder->set( 'F_CREATE_IDX', _elm( $param, 'F_CREATE_IDX' ) );
        $builder->set( 'F_CREATE_IP', _elm( $param, 'F_CREATE_IP' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }

        return $aReturn;

    }

    public function getFormsMaxSort( $menu )
    {
        $aReturn                                    = false;
        if( empty( $menu ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS' );
        $builder->select( 'IFNULL( MAX( F_SORT ), 0 ) MAX' );
        $builder->where( 'F_MENU', $menu );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'MAX' );
        }
        return $aReturn;
    }

    public function getFieldAndTitles( $table )
    {
        $aReturn                                    = [];

        if( empty( $table ) ){
            return $aReturn;
        }
        $query                                      = $this->db->query("
            SELECT COLUMN_NAME, COLUMN_COMMENT
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '$table'
        ");

        $aReturn                                   = $query->getResultArray();
        return $aReturn;

    }

    public function getExcelFormLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'DOWNLOAD_FORMS F' );
        $builder->select( 'F.*' );
        $builder->select( 'ADM.MB_USERID, ADM.MB_USERNAME' );
        $builder->join( 'ADMIN_MEMBER ADM', 'F.F_CREATE_IDX = ADM.MB_IDX', 'left' );

        if( !empty( _elm( $param, 'F_MENU' ) ) ){
            $builder->where('F.F_MENU', _elm( $param, 'F_MENU' ) );
        }
        if (isset($param['F_STATUS']) && $param['F_STATUS'] !== null) {
            $builder->where('F.F_STATUS', _elm($param, 'F_STATUS'));
        }
        if (!empty($param['F_TITLE'])) {
            $builder->like('F.F_TITLE', _elm( $param, 'F_TITLE' ), 'both' );

        }
        if (!empty($param['MB_USERNAME'])) {
            $builder->where('ADM.MB_USERNAME', _elm( $param, 'MB_USERNAME' ) );
        }
        if (!empty($param['MB_USERID'])) {
            $builder->where('ADM.MB_USERID', _elm( $param, 'MB_USERID' ) );
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