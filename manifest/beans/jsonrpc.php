<?php

return [

    // JsonRpc客户端
    [
        // 类路径
        'class'      => \Mix\JsonRpc\Client::class,
        // 属性注入
        'properties' => [
            // 拨号器
            'dialer' => \Mix\JsonRpc\Dialer::class,
        ],
    ],

    // JsonRpc拨号器
    [
        // 类路径
        'class'      => \Mix\JsonRpc\Dialer::class,
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
        // 名称
        'name'            => 'jsonRpcServer',
        // 类路径
        'class'           => \Mix\JsonRpc\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '127.0.0.1',
            // port
            9506,
        ],
    ],

];
