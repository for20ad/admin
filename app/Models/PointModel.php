<?php
namespace App\Models;

use CodeIgniter\Model;

class PointModel extends Model
{

    public function getPointBalance( int $mb_idx )
    {
        $aReturn = false;

        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table('POINT_BALANCE');
        $builder->select('BALANCE');
        $builder->where( 'MEMBER_IDX', $mb_idx );



        $query = $builder->get();

        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $result = $query->getRowArray();

            $aReturn = $result;
        }


        return $aReturn;

    }

    public function insertPointHistory( array $param )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table('POINT_HISTORY');
        $builder->insert( $param );
        //echo $this->db->getLastQuery();
        $aReturn = $this->db->insertID();
        return $aReturn;

    }

    public function updateBalance( array $param )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }
        $_query = "
            INSERT INTO POINT_BALANCE
                (MEMBER_IDX, BALANCE ) VALUES ( '"._elm( $param, 'MEMBER_IDX' )."' , '"._elm( $param, 'BALANCE' )."')
            ON DUPLICATE KEY UPDATE
                MEMBER_IDX = '"._elm( $param, 'MEMBER_IDX' )."' ,
                BALANCE = '"._elm( $param, 'BALANCE' )."'
        ";
        $aReturn = $this->db->query( $_query );
        return $aReturn;
    }
}

