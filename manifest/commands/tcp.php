<?php

return [

    'tcp' => [
        \App\Tcp\Commands\StartCommand::class,
        'usage'   => "\tStart the tcp server",
        'options' => [
            [['d', 'daemon'], 'usage' => "\tRun in the background"],
            [['h', 'host'], 'usage' => "\tListen to the specified host"],
            [['p', 'port'], 'usage' => "\tListen to the specified port"],
            [['r', 'reuse-port'], 'usage' => "Reuse port in multiple processes"],
        ],
    ],

];
