<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use Module\dahae\Controllers\Dahae;

class GetProductHeaders extends BaseCommand
{

    protected $group       = 'Custom';
    protected $name        = 'dahae:getProductHeaders';
    protected $description = 'Execute getProductHeaders from Dahae Controller';
    /**
     * The Command's Group
     *
     * @var string
     */


    /**
     * The Command's Name
     *
     * @var string
     */


    /**
     * The Command's Description
     *
     * @var string
     */


    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        // Dahae 컨트롤러 인스턴스 생성
        $controller = new \Module\dahae\Controllers\Dahae();

        // getProductHeaders 호출
        $response = $controller->getProductHeaders();

        // CLI 환경에서 결과 출력
        if (is_array($response)) {
            CLI::write(json_encode($response, JSON_PRETTY_PRINT), 'green');
        } else {
            CLI::write($response, 'green');
        }
    }
}
