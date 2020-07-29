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
            'dispatcher' => ['ref' => 'eventDispatcher'],
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
            // 最大连接数, 0为无限制
            'maxOpen'     => 50,
            // 最多可空闲连接数
            'maxIdle'     => 10,
            // 连接可复用的最长时间, 0为无限制
            'maxLifetime' => 0,
            // 等待新连接超时时间, 0为无限制
            'waitTimeout' => 0.0,
            // 事件调度器
            'dispatcher'  => ['ref' => 'eventDispatcher'],
        ],
    ],

];
