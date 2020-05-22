<?php

return [

    // Http服务器
    [
        // 类路径
        'class'           => \Mix\Http\Server\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '127.0.0.1',
            // port
            9501,
            // ssl
            false,
        ],
    ],

    // Tcp服务器
    [
        // 类路径
        'class'           => \Mix\Server\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '127.0.0.1',
            // port
            9503,
            // ssl
            false,
        ],
    ],

    // Udp服务器
    [
        // 类路径
        'class'           => \App\Udp\Server\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // domain
            AF_INET,
            // address
            '127.0.0.1',
            // port
            9504,
        ],
    ],

];
