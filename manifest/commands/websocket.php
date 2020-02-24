<?php

return [

    'ws:start' => [
        \App\WebSocket\Commands\StartCommand::class,
        'description' => "Start service",
        'options'     => [
            [['d', 'daemon'], 'description' => "\tRun in the background"],
            [['h', 'host'], 'description' => "\tListen to the specified host"],
            [['p', 'port'], 'description' => "\tListen to the specified port"],
            [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
        ],
    ],

];
