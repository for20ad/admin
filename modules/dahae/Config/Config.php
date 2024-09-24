<?php

namespace Module\dahae\Config;
use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $dahae = [];
    public function __construct()
    {
        $dahaeApiUrl                                = 'https://erpapidev.dahaeinc.co.kr/';
        $this->dahae['apiKey']                      = '0OlhwBPmWKS/gqcx8TYn1SkHtFKjFDr37SgmZ8yNElI=';

        $this->dahae['apiUrl']                      = [];
        $this->dahae['apiUrl']['token']             = $dahaeApiUrl.'TMBjwtToken';
        $this->dahae['apiUrl']['modelHeader']       = $dahaeApiUrl.'TMBModelHeader';
        $this->dahae['apiUrl']['modelDetail']       = $dahaeApiUrl.'TMBModelDetail';
        $this->dahae['apiUrl']['orderList']         = $dahaeApiUrl.'TMBOrderADD';







    }
}