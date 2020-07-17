<?php

return [

    'he' => [
        \App\Console\Commands\HelloCommand::class,
        'usage'   => "\tEcho demo",
        'options' => [
            [['n', 'name'], 'usage' => 'Your name'],
            ['say', 'usage' => "\tSay ..."],
        ],
    ],

    'co' => [
        \App\Console\Commands\CoroutineCommand::class,
        'usage' => "\tCoroutine demo",
    ],

    'wg' => [
        \App\Console\Commands\WaitGroupCommand::class,
        'usage' => "\tWaitGroup demo",
    ],

    'cp' => [
        \App\Console\Commands\WorkerPoolCommand::class,
        'usage' => "\tWorker pool demo",
    ],

    'cpd' => [
        \App\Console\Commands\WorkerPoolDaemonCommand::class,
        'usage'   => "\tWorker pool daemon demo",
        'options' => [
            [['d', 'daemon'], 'usage' => 'Run in the background'],
        ],
    ],

    'ti' => [
        \App\Console\Commands\TimeCommand::class,
        'usage' => "\tTimer and Ticker demo",
    ],

];
