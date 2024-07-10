<?php
#------------------------------------------------------------------
# MenuModel.php
# menu 모델
# 김우진
# 2024-06-27 13:26:45
# @Desc : 메뉴관련 모델
#------------------------------------------------------------------

namespace Module\setting\Models;
use Config\Services;
use CodeIgniter\Model;

class MenuModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function updateMenu( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $param, 'MENU_NAME' ) ) === true && empty( _elm( $param, 'MENU_LINK' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->set( 'MENU_GROUP_ID', _elm( $param, 'MENU_GROUP_ID' ) );
        $builder->set( 'MENU_NAME', _elm( $param, 'MENU_NAME' ) );
        $builder->set( 'MENU_CUSTOM_TAG', _elm( $param, 'MENU_CUSTOM_TAG' ) );
        $builder->set( 'MENU_LINK', _elm( $param, 'MENU_LINK' ) );
        $builder->set( 'MENU_LINK_TARGET', _elm( $param, 'MENU_LINK_TARGET' ) );
        $builder->set( 'MENU_DISPLAY_MEMBER_GROUP', _elm( $param, 'MENU_DISPLAY_MEMBER_GROUP' ) );
        $builder->set( 'MENU_SORT', _elm( $param, 'MENU_SORT' ) );
        $builder->set( 'MENU_STATUS', _elm( $param, 'MENU_STATUS' ) );
        $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function deleteMenu($param = [])
    {
        $aReturn                                    = false;
        if (empty($param) === true){
            return $aReturn;
        }
        if( empty( _elm( $param, 'MENU_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->where( 'MENU_IDX', _elm( $param, 'MENU_IDX' ) );
        $builder->orWhere( 'MENU_PARENT_IDX', _elm( $param, 'MENU_IDX' ) );

        $aReturn                                    = $builder->delete();

        return $aReturn;
    }

    public function getMenuLists( $status = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->select( 'MENU_IDX, MENU_PARENT_IDX, MENU_GROUP_ID, MENU_NAME, MENU_CUSTOM_TAG, MENU_LINK, MENU_LINK_TARGET' );
        $builder->select( 'MENU_DISPLAY_MEMBER_GROUP, MENU_SORT, MENU_STATUS' );


        if (  empty($status) === false)
        {
            $builder->where( 'MENU_STATUS', $status );
        }

        $builder->orderBy( 'MENU_PARENT_IDX',  'ASC' );
        $builder->orderBy( 'MENU_SORT', 'ASC' );
        $builder->orderBy( 'MENU_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function writeMenu( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->set( 'MENU_PARENT_IDX', _elm( $param, 'MENU_PARENT_IDX' ) );
        $builder->set( 'MENU_GROUP_ID', _elm( $param, 'MENU_GROUP_ID' ) );
        $builder->set( 'MENU_NAME', _elm( $param, 'MENU_NAME' ) );
        $builder->set( 'MENU_CUSTOM_TAG', _elm( $param, 'MENU_CUSTOM_TAG' ) );
        $builder->set( 'MENU_LINK', _elm( $param, 'MENU_LINK' ) );
        $builder->set( 'MENU_LINK_TARGET', _elm( $param, 'MENU_LINK_TARGET' ) );
        $builder->set( 'MENU_DISPLAY_MEMBER_GROUP', _elm( $param, 'MENU_DISPLAY_MEMBER_GROUP' ) );
        $builder->set( 'MENU_SORT', _elm( $param, 'MENU_SORT' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }


    // 관리자 전체 메뉴 가져오기
    public function getAdminMenuLists($status = 0)
    {

        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'ADMIN_MENU' );
        $builder->select( 'MENU_IDX, MENU_PARENT_IDX, MENU_GROUP_ID, MENU_NAME, MENU_CUSTOM_TAG, MENU_LINK, MENU_LINK_TARGET' );
        $builder->select( 'MENU_DISPLAY_MEMBER_GROUP, MENU_SORT, MENU_STATUS' );

        if (empty($status) === false)
        {
            $builder->where( 'MENU_STATUS', $status );
        }

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

    public function getAdminMenuListsByGroupId($group_id = '')
    {
        $aReturn                                    = [];
        if( empty( $group_id ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->select( 'MENU_IDX, MENU_PARENT_IDX, MENU_GROUP_ID, MENU_NAME, MENU_CUSTOM_TAG, MENU_LINK, MENU_LINK_TARGET' );
        $builder->select( 'MENU_DISPLAY_MEMBER_GROUP, MENU_SORT, MENU_STATUS' );
        $builder->where( 'MENU_STATUS', 1 );
        $builder->where("MENU_DISPLAY_MEMBER_GROUP REGEXP '(^|,)".$group_id."(,|$)'", null, false);

        $builder->orderBy( 'MENU_PARENT_IDX', 'ASC' );
        $builder->orderBy( 'MENU_SORT', 'ASC' );
        $builder->orderBy( 'MENU_IDX', 'ASC' );

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getAdminMenuListsByIdx($idx = 0)
    {

        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MENU' );

        $builder->select( 'MENU_IDX, MENU_PARENT_IDX, MENU_GROUP_ID, MENU_NAME, MENU_CUSTOM_TAG, MENU_LINK, MENU_LINK_TARGET' );
        $builder->select( 'MENU_DISPLAY_MEMBER_GROUP, MENU_SORT, MENU_STATUS' );
        $builder->where( 'MENU_STATUS', 1 );
        $builder->where( 'MENU_IDX', $idx );

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