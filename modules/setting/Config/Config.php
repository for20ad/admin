<?php

namespace Module\setting\Config;
use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $menu = [];

    public function __construct()
    {
        // Initialize menu
        $this->menu['menu_group_id'] = 'manage';

        $this->menu['status'] = [];
        $this->menu['status'][1] = '노출';
        $this->menu['status'][0] = '비노출';

        $this->menu['sort'] = array_combine(range(1, 99), range(1, 99));

        $this->menu['target'] = [];
        $this->menu['target']['_self'] = '_self';
        $this->menu['target']['_blank'] = '_blank';
    }
}
