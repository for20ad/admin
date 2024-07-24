<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class DeliveryModel extends Model
{
    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }

    public function insertDelivryCompany( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

    public function getDeliveryCompany()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        $builder->orderBy( 'D_SORT', 'ASC' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function updateDeliveryCompany( $param =[] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        foreach( $param as $key => $val ){
            if( $key == 'D_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getDeliveryCompanyByIdx($idx = 0)
    {

        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );

        $builder->where( 'D_IDX', $idx );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function setDeliveryCompanySort( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        $builder->set( 'D_SORT', _elm( $param, 'D_SORT' ) );
        $builder->where( 'D_IDX', _elm( $param, 'D_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function deleteDeliveryCompany( $param  = [])
    {
        $aReturn                                    = false;
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        $builder->where( 'D_IDX', _elm( $param, 'D_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function getDeliveryCompanyMaxSortNum()
    {
        $aReturn                                    = 0;
        $builder                                    = $this->db->table( 'DELIVERY_COMPANY' );
        $builder->select( 'MAX( D_SORT ) MAX_SORT' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
            $aReturn                                = _elm( $aReturn, 'MAX_SORT' );
        }
        return $aReturn;
    }
}