<?php

return [

    'he' => [
        \App\Console\Commands\HelloCommand::class,
        'description' => "\tEcho demo",
        'options'     => [
            [['n', 'name'], 'description' => 'Your name'],
            ['say', 'description' => "\tSay ..."],
        ],
    ],

    'co' => [
        \App\Console\Commands\CoroutineCommand::class,
        'description' => "\tCoroutine demo",
    ],

    'wg' => [
        \App\Console\Commands\WaitGroupCommand::class,
        'description' => "\tWaitGroup demo",
    ],

    'cp' => [
        \App\Console\Commands\CoroutinePoolCommand::class,
        'description' => "\tCoroutine pool demo",
    ],

    'cpd' => [
        \App\Console\Commands\CoroutinePoolDaemonCommand::class,
        'description' => "\tCoroutine pool daemon demo",
        'options'     => [
            [['d', 'daemon'], 'description' => 'Run in the background'],
        ],
    ],

    'ti' => [
        \App\Console\Commands\TimerCommand::class,
        'description' => "\tTimer demo",
    ],

];
