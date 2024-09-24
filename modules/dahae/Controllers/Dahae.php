<?php
namespace Module\dahae\Controllers;
use Module\core\Controllers\CoreController;
use Module\dahae\Models\DahaeModel;
use Config\Services;
use DOMDocument;
use Module\dahae\Config\Config as DahaeConfig;

use Exception;

class Dahae extends CoreController
{
    protected $dahaeConfig;
    public function __construct()
    {
        parent::__construct();
        $this->dahaeConfig                          = new DahaeConfig();
    }

    public function getAuthToken()
    {
        $request                                    = '';
        $aConfig                                    = $this->dahaeConfig->dahae;
        $apiKey                                     = _elm( $aConfig, 'apiKey' );

        $url                                        = _elm( _elm( $aConfig, 'apiUrl' ), 'token' );
        $url                                       .= '?apiKey='.$apiKey;

        #------------------------------------------------------------------
        # TODO: GET
        #------------------------------------------------------------------
        $response                                   = $this->_curl(  $url, 'GET','' , ['apiKey' => _elm( $aConfig, 'apiKey' )] );




        #------------------------------------------------------------------
        # TODO: PUT
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'PUT', ['Content-Type: application/json'], $jwtToken, ['name' => 'updatedItem', 'price' => 150]);

        #------------------------------------------------------------------
        # TODO: DELETE
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'DELETE', $jwtToken );

        #------------------------------------------------------------------
        # TODO: PATCH
        #------------------------------------------------------------------
        //$response = $this->_curl('https://api.example.com/items/1', 'PATCH', $jwtToken, ['price' => 120]);



        return _elm($response , 'process');


    }

    public function getProductHeaders()
    {
        $jwtToken                                   = $this->getAuthToken();

        $aConfig                                    = $this->dahaeConfig->dahae;
        $url                                        = _elm( _elm( $aConfig, 'apiUrl' ), 'modelHeader' );
        $data                                       = [
            'regDate'                               => '2021-01-01',
            'page'                                  => 1,
        ];

        #------------------------------------------------------------------
        # TODO: POST
        #------------------------------------------------------------------
        $response = $this->_curl( $url, 'GET', $jwtToken, $data);
        $data     = json_decode( _elm( $response, 'process' ), true );
        echo "<pre>";
        print_r( $data );
        echo "</pre>";

    }

    private function _curl($_urlParams, $method = 'GET', $jwtToken = '', $data = [])
    {
        $curl = curl_init();

        // 기본 헤더 설정 (JWT 토큰 포함)
        $headers = [
            'Content-Type: application/json'
        ];
        if (!empty($jwtToken)) {
            $headers[] = 'Authorization: Bearer ' . $jwtToken;  // JWT 토큰 추가
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // URL 설정
        curl_setopt($curl, CURLOPT_URL, $_urlParams);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // HTTP 메소드에 따라 cURL 옵션 설정
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // POST 데이터 설정
                }
                break;

            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // PUT 데이터 설정
                }
                break;

            case 'PATCH':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // PATCH 데이터 설정
                }
                break;

            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // DELETE 데이터 설정
                }
                break;

            default: // GET 요청의 경우
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
        }

        // 응답 실행
        $response = curl_exec($curl);

        // cURL 오류 체크
        if (curl_errno($curl)) {
            echo 'cURL Error: ' . curl_error($curl);
        }

        curl_close($curl);

        // 응답을 JSON으로 디코딩
        $data = json_decode($response, true);

        return $data;
    }


    public function pageTwo()
    {
        $pageDatas = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\two';
        $pageParam['pageLayout'] = 'none';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function pageThree()
    {
        $pageDatas = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\three';
        $pageParam['pageLayout'] = 'none';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function pagefour()
    {
        $pageDatas = [];
        $this->response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------

        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\four';
        $pageParam['pageLayout'] = '';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

}
