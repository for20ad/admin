<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class MemberModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function getMemberGroup()
    {
        $aReturn = [];

        $builder = $this->db->table( 'ADMIN_MEMBER_GROUP' );
        $builder->select( 'MB_GROUP_IDX, MB_GROUP_NAME' );
        $builder->orderBy( 'MB_GROUP_ORDER', 'ASC' );
        $builder->orderBy( 'MB_GROUP_IDX', 'ASC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult = $query->getResultArray();
            foreach( $aResult as $vData )
            {
                $aReturn[_elm($vData, 'MB_GROUP_IDX')] = _elm($vData, 'MB_GROUP_NAME');
            }
        }
        return $aReturn;
    }

    public function getAdminMemberGroup( $level = 0 )
    {
        $aReturn = [];

        $builder = $this->db->table( 'ADMIN_MEMBER_GROUP' );
        $builder->select( 'MB_GROUP_IDX, MB_GROUP_NAME' );
        $builder->where( 'MB_GROUP_IDX', $level );
        $builder->orderBy( 'MB_GROUP_ORDER', 'ASC' );
        $builder->orderBy( 'MB_GROUP_IDX', 'ASC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult = $query->getRowArray();
            $aReturn = _elm( $aResult, 'MB_GROUP_NAME' );
        }
        return $aReturn;
    }
}