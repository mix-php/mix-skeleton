<?php

return [

    // Http服务器
    [
        // 类路径
        'class'           => \Mix\Http\Server\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '0.0.0.0',
            // port
            0,
            // ssl
            false,
        ],
        // 属性注入
        'properties'      => [
            // 事件调度器
            'dispatcher' => ['ref' => 'event'],
        ],
    ],

];
