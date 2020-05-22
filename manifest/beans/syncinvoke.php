<?php

return [

    // SyncInvoke服务器
    [
        // 类路径
        'class'           => \Mix\SyncInvoke\Server::class,
        // 构造函数注入
        'constructorArgs' => [
            // port
            9505,
        ],
        // 属性注入
        'properties'      => [
            // 事件调度器
            'dispatcher' => ['ref' => 'dispatcher'],
        ],
    ],

    // SyncInvoke客户端
    [
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\SyncInvoke\Client\Client::class,
        // 构造函数注入
        'constructorArgs' => [
            // port
            9505,
            // timeout
            5.0,
            // invokeTimeout
            10.0,
        ],
        // 属性注入
        'properties'      => [
            // 最多可空闲连接数
            'maxIdle'   => 5,
            // 最大连接数
            'maxActive' => 50,
        ],
    ],

];
