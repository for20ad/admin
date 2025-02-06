<?php
namespace App\Libraries;

use Config\Talk;
use App\Models\KakaoModel;
use Shared\Config as SharedConfig;
use App\Libraries\FcmLib;
use App\Models\LicenseModel;
use CodeIgniter\HTTP\CURLRequest;

class LogisCrawling
{
    private $sharedConfig;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->sharedConfig                        = new SharedConfig();
    }

    public function logenLogis( $trackingNumber )
    {
        // API URL
        $url = 'https://apis.tracker.delivery/graphql';

        // GraphQL Query
        $query = <<<GRAPHQL
        query Track(\$carrierId: ID!, \$trackingNumber: String!) {
          track(carrierId: \$carrierId, trackingNumber: \$trackingNumber) {
            lastEvent {
              time
              status {
                name
              }
            }
            events(last: 10) {
              edges {
                node {
                  time
                  status {
                    name
                  }
                }
              }
            }
          }
        }
        GRAPHQL;

        // GraphQL Variables
        $variables = [
            'carrierId' => 'kr.logen',
            'trackingNumber' => $trackingNumber,
        ];

        // HTTP Request Payload
        $payload = [
            'query' => $query,
            'variables' => $variables,
        ];

        // Client ID와 Client Secret
        $clientId = '1dhod7ma2mruovg0t1l1ldqi34';
        $clientSecret = 'ffb5miqu0rg80eks11onu6sd3vmpnc9eopiqr6etqnvas6fqqik';

        // cURL 초기화
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: TRACKQL-API-KEY ' . $clientId . ':' . $clientSecret,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // API 호출
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 오류 처리
        if ($httpCode !== 200) {
            return [
                'error' => 'API 호출 실패',
                'httpCode' => $httpCode,
                'response' => json_decode($response, true),
            ];
        }

        // JSON 응답 반환
        return json_decode($response, true);
    }

    public function logenLogis2( $trackingNumber )
    {
        // 로젠택배 URL
        //$url = "https://www.ilogen.com/m/personal/trace/91175140346";
        $url = "https://www.ilogen.com/web/personal/trace/".trim($trackingNumber);
        // cURL 초기화
        $client = \Config\Services::curlrequest([
            'verify' => false, // SSL 인증 무시 (필요 시)
        ]);

        // HTTP GET 요청
        $response = $client->get($url);

        // 응답 HTML
        $html = $response->getBody();

        // DOMDocument를 사용한 데이터 파싱
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // HTML 오류 무시
        $dom->loadHTML($html);
        libxml_clear_errors();

        // tbody 추출
        $xpath = new \DOMXPath($dom);
        $rows = $xpath->query("//table[contains(@class, 'tkInfo')]//tbody//tr");

        $data = [];
        foreach ($rows as $row) {
            $columns = $row->getElementsByTagName('td');
            $temp = [];
            foreach ($columns as $column) {
                $temp[] = trim($column->textContent);
            }
            $data[] = $temp;
        }

        // JSON 응답
        return $data;
    }
}