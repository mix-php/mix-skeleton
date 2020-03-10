<?php

return [

    // JsonRpc客户端
    [
        // 类路径
        'class'      => \Mix\JsonRpc\Client\Connection::class,
        // 初始方法
        'initMethod' => 'connect',
        // 属性注入
        'properties' => [
            // host
            'host' => '127.0.0.1',
            // port
            'port' => 9506,
        ],
    ],

    // JsonRpc服务器
    [
        // 类路径
        'class'           => \Mix\JsonRpc\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '0.0.0.0',
            // port
            9506,
        ],
        // 属性注入
        'properties'      => [
            // 事件调度器
            'dispatcher' => ['ref' => 'event'],
        ],
    ],

];
