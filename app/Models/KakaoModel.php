<?php
namespace App\Models;

use CodeIgniter\Model;

class KakaoModel extends Model
{
    protected $kakaoDB;
    // public function __construct()
    // {
    //     parent::__construct();

    //     // KakaoDB 로드
    //     $this->db = \Config\Database::connect('kakaoDB');
    // }

    public function __construct()
    {

        $this->kakaoDB                              = \Config\Database::connect('kakaoDB', false);
    }

    public function getTemplate( $code )
    {
        $aReturn                                    = [];

        $builder                                    = $this->kakaoDB->table('TBL_TEMPLATE');

        $builder->where('TEMPLATE_CODE' , $code);
        $builder->where('INSPECTION_STATUS','APR');

        $query                                      = $builder->get();

        if ($this->kakaoDB->affectedRows())
        {
            $result                                 = $query->getRowArray();
            $aReturn                                = $result;
        }

        return $aReturn;
    }

    public function INSERT_ATALK( $param = [] )
    {
        $builder                                    = $this->kakaoDB->table('SUREData');

        $builder->insert( $param);

        return $this->kakaoDB->insertID();

    }


}

