<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     $this->setCorsHeaders();

    //     // OPTIONS 메서드의 경우 바로 200 응답을 반환하고 종료
    //     if ($request->getMethod() === 'options') {
    //         header("HTTP/1.1 200 OK");
    //         exit();
    //     }
    // }

    public function before(RequestInterface $request, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin');

        $allowedOrigins = ['http://localhost:3000', 'https://api.brav.co.kr','http://localhost', 'https://localhost'];

        if (in_array($origin, $allowedOrigins)) {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin', $origin);
            $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
            $response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
            $response->setHeader('Access-Control-Allow-Credentials', 'true');

            if ($request->getMethod() === 'options') {
                return $response->setStatusCode(ResponseInterface::HTTP_OK);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $this->setCorsHeaders();
    }

    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
    }
}