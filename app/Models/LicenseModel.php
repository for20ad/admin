<?php
namespace App\Models;

use CodeIgniter\Model;

class LicenseModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getUserDeviceLists( $mb_idx )
    {
        $aReturn                                    = [];
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'DEVICE_INFO' );

        $builder->where( 'DI_MB_IDX',               $mb_idx );
        $builder->where( 'DI_PUSH_TYPE1',           '1' );
        $builder->where( 'DI_PUSH_TYPE2',           '1' );
        $builder->where( 'DI_TEST_YN',              '0' );
        $builder->where( 'DI_STATUS',               '0' );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }



}

