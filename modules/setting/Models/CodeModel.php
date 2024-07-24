<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class CodeModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function getCodesByNameTop($param = [])
    {
        $aReturn                                    = [];

        if (empty($param)) {
            return $aReturn;
        }

        if (empty(_elm($param, 'C_NAME'))) {
            return $aReturn;
        }

        $builder                                    = $this->db->table('CODE');
        $builder->select('C_NAME, C_CODE, C_IDX');
        $builder->where('C_NAME', _elm($param, 'C_NAME'));
        $query                                      = $builder->get();

        if ($this->db->affectedRows()) {
            $parentResults                          = $query->getResultArray();

            if (!empty($parentResults)) {
                $parentIds                          = array_column($parentResults, 'C_IDX');
                $builder                            = $this->db->table('CODE');
                $builder->select('*');
                $builder->whereIn('C_PARENT_IDX', $parentIds);
                $query                              = $builder->get();

                if ($this->db->affectedRows()) {
                    $aReturn                        = $query->getResultArray();
                }
            }
        }

        return $aReturn;
    }



    public function updateCode( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $param, 'C_NAME' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'CODE' );

        $builder->set( 'C_NAME', _elm( $param, 'C_NAME' ) );
        $builder->set( 'C_SORT', _elm( $param, 'C_SORT' ) );
        $builder->set( 'C_STATUS', _elm( $param, 'C_STATUS' ) );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function deleteCode($param = [])
    {
        $aReturn                                    = false;
        if (empty($param) === true){
            return $aReturn;
        }
        if( empty( _elm( $param, 'C_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'CODE' );

        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
        $builder->orWhere( 'C_IDX', _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->delete();

        return $aReturn;
    }

    public function getCodeLists( $status = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'CODE' );

        $builder->select( 'C_IDX, C_PARENT_IDX, C_CODE, C_NAME, C_SORT, C_STATUS' );

        if (  empty($status) === false)
        {
            $builder->where( 'C_STATUS', $status );
        }

        $builder->orderBy( 'C_PARENT_IDX',  'ASC' );
        $builder->orderBy( 'C_SORT', 'ASC' );
        $builder->orderBy( 'C_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function sameChecked( $param = [] )
    {
        $aReturn                                    = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'CODE' );
        $builder->select('*');
        $builder->where( 'C_PARENT_IDX', _elm( $param, 'C_PARENT_IDX' ) );
        if( empty( _elm( $param, 'C_NAME' ) ) === false ){
            $builder->where( 'C_NAME', _elm( $param, 'C_NAME' ) );
        }
        if( empty( _elm( $param, 'C_CODE' ) ) === false ){
            $builder->where( 'C_CODE', _elm( $param, 'C_CODE' ) );
        }

        $aReturn                                    = $builder->countAllResults(false);

        return $aReturn;
    }

    public function getCodeOneCnt( $param = [] )
    {
        $aReturn                                    = [];

        if( empty( _elm( $param, 'C_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'CODE' );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
        $aReturn                                    = $builder->countAllResults(false);

        return $aReturn;
    }


    public function writeCode( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table('CODE');
        foreach( $param as $key => $val )
        {
            $builder->set( $key, $val);
        }

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getCodesByIdx($idx = 0)
    {

        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'CODE' );

        $builder->select( 'C_IDX, C_PARENT_IDX, C_CODE, C_NAME, C_SORT, C_STATUS' );

        $builder->where( 'C_IDX', $idx );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getAdminMenuListsByParentIdx($parent_idx = 0)
    {

        $aReturn                                    = [];
        if( empty( $parent_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MENU' );
        $builder->select( 'MENU_IDX, MENU_PARENT_IDX, MENU_GROUP_ID, MENU_NAME, MENU_CUSTOM_TAG, MENU_LINK, MENU_LINK_TARGET' );
        $builder->select( 'MENU_DISPLAY_MEMBER_GROUP, MENU_SORT, MENU_STATUS' );
        $builder->where( 'MENU_STATUS', 1 );
        $builder->where( 'MENU_PARENT_IDX', $parent_idx );

        $builder->orderBy( 'MENU_PARENT_IDX', 'ASC' );
        $builder->orderBy( 'MENU_SORT', 'ASC' );
        $builder->orderBy( 'MENU_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
}