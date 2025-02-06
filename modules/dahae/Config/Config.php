<?php

namespace Module\dahae\Config;

use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $dahae = [];

    public function __construct()
    {
        $dahaeTestApiUrl = 'https://erpapidev.dahaeinc.co.kr/';
        $dahaeRealApiUrl = 'https://erpapi.dahaeinc.co.kr/';

        $this->dahae['apiTestKey'] = '0OlhwBPmWKS/gqcx8TYn1SkHtFKjFDr37SgmZ8yNElI=';
        $this->dahae['apiRealKey'] = '0OlhwBPmWKS/gqcx8TYn1Sa3KOMLtv2heJU5xxrFaUM=';

        // 현재 사용하는 키 확인
        $currentApiKey = $this->getApiKey();

        // API URL 설정 (키에 따라 달라짐)
        $apiUrlBase = $currentApiKey === $this->dahae['apiTestKey'] ? $dahaeTestApiUrl : $dahaeRealApiUrl;

        $this->dahae['apiUrl'] = [
            'token'       => $apiUrlBase . 'TMBjwtToken',
            'modelHeader' => $apiUrlBase . 'TMBModelHeader',
            'modelDetail' => $apiUrlBase . 'TMBModelDetail',
            'orderList'   => $apiUrlBase . 'TMBOrderADD',
        ];
    }

    /**
     * 현재 사용할 API 키를 반환하는 함수
     * @return string
     */
    private function getApiKey()
    {
        // 기본적으로 테스트 키를 반환하도록 설정
        //return $this->dahae['apiTestKey'];

        // 실제 키를 사용하려면 다음과 같이 수정:
        return $this->dahae['apiRealKey'];
    }
}
