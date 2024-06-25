<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    public $commands = [
        'start:websocket' => \App\Commands\WebSocketCommand::class,
    ];
}
