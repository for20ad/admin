<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class MembershipModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function setMembershipGradeSort( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'MEMBER_GRADE' );
        $builder->set( 'G_SORT', _elm( $param, 'G_SORT' ) );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $sReturn = $builder->update();
        return $aReturn;
    }

    public function getMembershipGradeListsByIdx($idx = 0)
    {

        $aReturn = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'MEMBER_GRADE' );

        $builder->where( 'G_IDX', $idx );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getMembershipGrade()
    {
        $aReturn = [];

        $builder = $this->db->table( 'MEMBER_GRADE' );
        $builder->orderBy( 'G_SORT', 'ASC' );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }

    public function updateMembershipGrade( $param =[] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'MEMBER_GRADE' );
        foreach( $param as $key => $val ){
            if( $key == 'G_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn = $builder->update();
        return $aReturn;
    }
    public function insertMembershipGrade( $param =[] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'MEMBER_GRADE' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn = $builder->insert();
        return $aReturn;
    }

}