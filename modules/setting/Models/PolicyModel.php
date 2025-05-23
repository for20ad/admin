<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class PolicyModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function getMallPolicyDatas()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'DEFAULT_TERMS' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }


    public function getPolicy()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'DEFAULT_POLICY' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function updatePolicy( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_POLICY' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $builder->where( 'P_IDX', 1 );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function insertPolicy( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_POLICY' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

    public function updateTerms( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_TERMS' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $builder->where( 'T_IDX', 1 );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function insertTerms( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_TERMS' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

}