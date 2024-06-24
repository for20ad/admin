<?php
namespace Module\admin\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class SettingModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getMenuInfo( $param = [] )
    {
        $aReturn = false;
        if(  empty( $param ) === true){
            return $aReturn;
        }
        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;
    }
    public function deleteMenu( $menu_idx )
    {
        $aReturn = false;
        if(  empty( $menu_idx ) === true){
            return $aReturn;
        }

        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->where( 'MENU_IDX', $menu_idx );

        $aReturn = $builder->delete();
        return $aReturn;
    }

    public function getChildMenus( $parentIdx )
    {
        $aReturn = false;
        if( empty( $parentIdx ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->where( 'MENU_PARENT_IDX', $parentIdx );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }

    public function patchFrontMenu( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'FRONT_MENU' );

        foreach( $param as $key => $data )
        {
            if( $key == "MENU_IDX" ){
                $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );
            }else{
                $builder->set( $key, $data );
            }
        }

        $aReturn = $builder->update();

        return $aReturn;
    }

    public function getMenuLists( $param = [] )
    {
        $aReturn = [];
        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->select( '*' );
        $builder->where( 'MENU_STATUS', 1 );

        if( empty( _elm( $param, 'MENU_GROUP' ) ) === false ){
            $builder->where( 'MENU_GROUP', _elm( $param, 'MENU_GROUP' ) );
        }
        $builder->orderBy( 'MENU_GROUP',  'ASC' );
        $builder->orderBy( 'MENU_SORT',  'ASC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getLastMenuSortNum( $param = [] )
    {
        $aReturn = false;
        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->select( 'MENU_SORT' );
        $builder->where( 'MENU_PARENT_IDX', _elm( $param, 'MENU_PARENT_IDX' ) );
        $builder->where( 'MENU_GROUP', _elm( $param, 'MENU_GROUP' ) );
        $builder->where( 'MENU_STATUS', 1 );
        $builder->orderBy( 'MENU_SORT',  'DESC' );

        $query = $builder->get();


        if ($this->db->affectedRows())
        {
            $aResult = $query->getRowArray();
            $aReturn = _elm( $aResult, 'MENU_SORT' );
        }
        return $aReturn;

    }

    public function setFrontMenu( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }


        $builder = $this->db->table('FRONT_MENU');
        foreach( $param as $key => $val )
        {
            $builder->set( $key, $val);
        }

        $aResult = $builder->insert();
        if( $aResult ){
            $aReturn = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getMenuOneTree( $param = [] )
    {
        $aReturn = [];

        if( empty( _elm( $param, 'MENU_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getMenuOneCnt( $param = [] )
    {
        $aReturn = false;

        if( empty( _elm( $param, 'MENU_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );
        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }



    public function sameChecked( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'FRONT_MENU' );
        $builder->select('*');
        $builder->where( 'MENU_PARENT_IDX', _elm( $param, 'MENU_PARENT_IDX' ) );
        $builder->where( 'MENU_GROUP', _elm( $param, 'MENU_GROUP' ) );
        $builder->where( 'MENU_NAME', _elm( $param, 'MENU_NAME' ) );

        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }

    public function reSort($parentIdx, $currentSort, $newSort)
    {
        $aReturn = false;
        if (isset($parentIdx) === false || empty($currentSort) || empty($newSort)) {
            return $aReturn;
        }

        $builder = $this->db->table('FRONT_MENU');
        $builder->where('MENU_PARENT_IDX', $parentIdx);

        if ($currentSort < $newSort) {
            // currentSort가 newSort보다 작은 경우
            $builder->where('MENU_SORT >', $currentSort);
            $builder->where('MENU_SORT <=', $newSort);
            $builder->set('MENU_SORT', 'MENU_SORT - 1', false);
        } else {
            // currentSort가 newSort보다 큰 경우
            $builder->where('MENU_SORT <', $currentSort);
            $builder->where('MENU_SORT >=', $newSort);
            $builder->set('MENU_SORT', 'MENU_SORT + 1', false);
        }

        $aReturn = $builder->update();
        return $aReturn;
    }


}