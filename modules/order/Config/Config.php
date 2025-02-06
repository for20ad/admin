<?php

namespace Module\design\Config;

use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $design = [];
    public function __construct()
    {

        $this->design['status']                     = [];
        $this->design['status']['Y']                = '사용';
        $this->design['status']['N']                = '사용안함';

        $this->design['icon_gbn']                   = [];
        $this->design['icon_gbn']['L']              = '무제한용';
        $this->design['icon_gbn']['P']              = '기간제한용';



    }
}