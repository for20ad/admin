<?php
namespace Module\token\Models;

use Config\Services;
use CodeIgniter\Model;

class TokenModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }

    public function getRefreshToken( $param = [] )
    {
        $aReturn          = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'TOKEN TN' );
        $builder->select("TN.*");

        if( empty( _elm( $param, 'REFRESH_TOKEN' ) ) === false ){
            $builder->where('TN.REFRESH_TOKEN', _elm( $param, 'REFRESH_TOKEN' ) );
        }

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $result = $query->getRowArray();

            $aReturn = $result;
        }
        // echo $this->db->getLastQuery();
        return $aReturn;
    }

    public function registRefreshToken( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table('TOKEN');

        $builder->insert($param);

        $aReturn = $this->db->insertID();
        // echo $this->db->getLastQuery();

        return $aReturn;
    }

}