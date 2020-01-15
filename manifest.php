<?php

// 应用清单
return [

    // 应用名称
    'appName'    => 'mix-app',

    // 应用版本
    'appVersion' => '0.0.0',

    // 应用调试
    'appDebug'   => getenv('APP_DEBUG'),

    // 基础路径
    'basePath'   => __DIR__,

    // 依赖路径
    'beanPath'   => __DIR__ . '/beans',

    // 协程配置
    'coroutine'  => [
        true,
        [
            'max_coroutine' => 300000,
            'hook_flags'    => SWOOLE_HOOK_ALL,
        ],
    ],

    // 命令
    'commands'   => [

        /** Console */
        'he'         => [
            \App\Console\Commands\HelloCommand::class,
            'description' => "\tEcho demo",
            'options'     => [
                [['n', 'name'], 'description' => 'Your name'],
                ['say', 'description' => "\tSay ..."],
            ],
        ],
        'co'         => [
            \App\Console\Commands\CoroutineCommand::class,
            'description' => "\tCoroutine demo",
        ],
        'wg'         => [
            \App\Console\Commands\WaitGroupCommand::class,
            'description' => "\tWaitGroup demo",
        ],
        'cp'         => [
            \App\Console\Commands\CoroutinePoolCommand::class,
            'description' => "\tCoroutine pool demo",
        ],
        'cpd'        => [
            \App\Console\Commands\CoroutinePoolDaemonCommand::class,
            'description' => "\tCoroutine pool daemon demo",
            'options'     => [
                [['d', 'daemon'], 'description' => 'Run in the background'],
            ],
        ],
        'ti'         => [
            \App\Console\Commands\TimerCommand::class,
            'description' => "\tTimer demo",
        ],

        /** Http */
        'http:start' => [
            \App\Http\Commands\StartCommand::class,
            'description' => "Start service",
            'options'     => [
                [['d', 'daemon'], 'description' => "\tRun in the background"],
                [['h', 'host'], 'description' => "\tListen to the specified host"],
                [['p', 'port'], 'description' => "\tListen to the specified port"],
                [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
            ],
        ],

        /** WebSocket */
        'ws:start'   => [
            \App\WebSocket\Commands\StartCommand::class,
            'description' => "Start service",
            'options'     => [
                [['d', 'daemon'], 'description' => "\tRun in the background"],
                [['h', 'host'], 'description' => "\tListen to the specified host"],
                [['p', 'port'], 'description' => "\tListen to the specified port"],
                [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
            ],
        ],

        /** Tcp */
        'tcp:start'  => [
            \App\Tcp\Commands\StartCommand::class,
            'description' => "Start service",
            'options'     => [
                [['d', 'daemon'], 'description' => "\tRun in the background"],
                [['h', 'host'], 'description' => "\tListen to the specified host"],
                [['p', 'port'], 'description' => "\tListen to the specified port"],
                [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
            ],
        ],

        /** Udp */
        'udp:start'  => [
            \App\Udp\Commands\StartCommand::class,
            'description' => "Start service",
            'options'     => [
                [['d', 'daemon'], 'description' => "\tRun in the background"],
                [['h', 'host'], 'description' => "\tListen to the specified host"],
                [['p', 'port'], 'description' => "\tListen to the specified port"],
                [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
            ],
        ],

        /** SyncInvoke */
        'sync:start' => [
            \App\SyncInvoke\Commands\StartCommand::class,
            'description' => "Start service",
            'options'     => [
                [['d', 'daemon'], 'description' => "\tRun in the background"],
                [['p', 'port'], 'description' => "\tListen to the specified port"],
                [['r', 'reuse-port'], 'description' => "Reuse port in multiple processes"],
            ],
        ],

        /** JsonRpc */
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

    ],

];
