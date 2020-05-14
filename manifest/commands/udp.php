<?php

return [

    'udp' => [
        \App\Udp\Commands\StartCommand::class,
        'usage'   => "\tStart the udp server",
        'options' => [
            [['d', 'daemon'], 'usage' => "\tRun in the background"],
            [['a', 'addr'], 'usage' => "\tListen to the specified address"],
            [['p', 'port'], 'usage' => "\tListen to the specified port"],
            [['r', 'reuse-port'], 'usage' => "Reuse port in multiple processes"],
        ],
    ],

];
