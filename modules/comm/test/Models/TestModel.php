<?php
namespace Module\comm\test\Models;

use Config\Services;
use CodeIgniter\Model;

class TestModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function getMemberInfo( $memId )
    {
        $aReturn = [];
        $builder = $this->db->table( 'es_member' );
        $builder->select('*');
        $builder->where( 'memId', $memId );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;

    }

    public function setExchangeData( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }


        $builder = $this->db->table('EXCHANGE_DATA');
        foreach( $param as $key => $val )
        {
            $builder->set( $key, $val);
        }

        $aReturn = $builder->insert();
        return $aReturn;
    }

    public function sameChecked( $param = [] )
    {
        $aReturn = [];
        $builder = $this->db->table( 'EXCHANGE_DATA' );
        $builder->select('*');
        $builder->where( 'F_DATE', _elm($param, 'F_DATE') );
        $builder->where( 'F_LOC', _elm($param, 'F_LOC') );

        return $builder->countAllResults(false);
    }
}