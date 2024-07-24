<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class MembershipModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function saveGradeValuation( $param = [] )
    {
        $aReturn                                    = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_GRADE_VALUATION_CONF' );

        $sql                                        = $builder->set( $param )->getCompiledInsert();

        $sql                                       .= ' ON DUPLICATE KEY UPDATE ';
        $updateFields                               = [];
        foreach ($param as $key => $value) {
            if( $key != 'V_UNIQUE' ){
                $updateFields[]                     = "$key = VALUES($key)";
            }
        }
        $sql                                       .= implode(', ', $updateFields);

        $aReturn                                    = $this->db->query($sql);
        return $aReturn;

    }

    public function getMembershipGradeValuation()
    {
        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'MEMBER_GRADE_VALUATION_CONF' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function deleteMembershipGradeIcon( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'G_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->set( 'G_ICON_PATH', null );
        $builder->set( 'G_ICON_NAME', null );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function getMembershipGradeMaxSortNum()
    {
        $aReturn                                    = 0;
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->select( 'MAX( G_SORT ) MAX_SORT' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
            $aReturn                                = _elm( $aReturn, 'MAX_SORT' );
        }
        return $aReturn;
    }

    public function deleteMembershipGrade( $param  = [])
    {
        $aReturn                                    = false;
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function getMembershopGradeByIdx( $idx = 0 )
    {
        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->where( 'G_IDX', $idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function setMembershipGradeSort( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->set( 'G_SORT', _elm( $param, 'G_SORT' ) );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getMembershipGradeListsByIdx($idx = 0)
    {

        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );

        $builder->where( 'G_IDX', $idx );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getMembershipGrade()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->orderBy( 'G_SORT', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function updateMembershipGrade( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        foreach( $param as $key => $val ){
            if( $key == 'G_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function insertMembershipGrade( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

}