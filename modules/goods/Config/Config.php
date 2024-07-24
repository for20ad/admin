<?php

namespace Module\goods\Config;
use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $goods = [];
    public function __construct()
    {

        $this->goods['status']                      = [];
        $this->goods['status']['Y']                 = '사용';
        $this->goods['status']['N']                 = '사용안함';

        $this->goods['icon_gbn']                    = [];
        $this->goods['icon_gbn']['L']               = '무제한용';
        $this->goods['icon_gbn']['P']               = '기간제한용';



    }
}