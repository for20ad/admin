<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class CartModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function getCartSetting()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'DEFAULT_CART_SETTING' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function updateCartSetting( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_CART_SETTING' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $builder->where( 'C_IDX', 1 );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }
    public function insertCartSetting( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DEFAULT_CART_SETTING' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

}