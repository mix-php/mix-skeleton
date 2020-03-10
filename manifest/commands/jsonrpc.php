<?php

return [

    'jrpc:start' => [
        \App\JsonRpc\Commands\StartCommand::class,
        'description' => "Start service",
        'options'     => [
            [['d', 'daemon'], 'description' => "\tRun in the background"],
            [['h', 'host'], 'description' => "\tListen to the specified host"],
            [['p', 'tcp-port'], 'description' => "\tListen to the specified tcp port"],
            [['P', 'http-port'], 'description' => "\tListen to the specified http port"],
            [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
        ],
    ],

];
