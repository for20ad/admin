<?php
namespace App\Models;

use CodeIgniter\Model;

class MileageModel extends Model
{

    public function getMileageBalance( int $mb_idx )
    {
        $aReturn                                    = false;

        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table('MILEAGE_BALANCE');
        $builder->select('IDX, BALANCE');
        $builder->where( 'MEMBER_IDX', $mb_idx );



        $query                                      = $builder->get();

        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $result                                 = $query->getRowArray();

            $aReturn                                = $result;
        }


        return $aReturn;

    }

    public function getMileageHistory( array $param )
    {
        $aReturn                                    = false;

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table('MILEAGE_HISTORY');

        $builder->select("IDX, MEMBER_IDX, CASE WHEN TYPE=1 THEN '사용' ELSE '적립' END STATUS, AMOUNT, CREATED_AT");
        $builder->where( 'MEMBER_IDX', _elm( $param, 'MEMBER_IDX' ) );
        if( empty( _elm( $param, 'START' ) ) === false && empty( _elm( $param, 'END' )) === false ){
            $builder->where( "DATE_FORMAT( CREATED_AT, '%Y%m' ) >= ", _elm( $param, 'START' ) );
            $builder->where( "DATE_FORMAT( CREATED_AT, '%Y%m' ) <= ", _elm( $param, 'END' ) );
        }

        $query                                      = $builder->get();

        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $result                                 = $query->getResultArray();
            $aReturn                                = $result;
        }


        return $aReturn;

    }

    public function insertMileageData( array $param )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table('MILEAGE_BALANCE');

        $builder->set( 'RESET_AT', 'DATE_ADD(LAST_DAY(NOW() - INTERVAL 1 MONTH), INTERVAL 1 DAY)', false );
        $builder->set( 'MEMBER_IDX', _elm( $param, 'MEMBER_IDX' ) );
        $builder->set( 'BALANCE', _elm( $param, 'BALANCE' ) );
        $builder->set( 'CREATE_AT', _elm( $param, 'CREATED_AT' ) );
        $builder->insert();

        //echo $str = $this->db->getLastQuery();
        $aReturn                                    = $this->db->insertID();

        return $aReturn;

    }

    public function insertMileageHistory( array $param )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table('MILEAGE_HISTORY');
        $builder->insert( $param );
        //echo $str = $this->db->getLastQuery();
        $aReturn                                    = $this->db->insertID();
        return $aReturn;

    }

    public function updateBalance( array $param )
    {
        $aReturn                                    = false;
        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table('MILEAGE_BALANCE');
        $builder->set( 'BALANCE', _elm( $param, 'BALANCE' ) );
        $builder->where( 'MEMBER_IDX', _elm( $param, 'MEMBER_IDX' ) );

        $aReturn                                    = $builder->update();
        //echo $this->db->getLastQuery();
        return $aReturn;
    }
}

