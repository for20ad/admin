<?php
namespace Module\design\Models;

use Config\Services;
use CodeIgniter\Model;

class LogoModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getLogoData()
    {
        $aReturn                                      = [];
        $builder                                    = $this->db->table( 'LOGOS' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

}