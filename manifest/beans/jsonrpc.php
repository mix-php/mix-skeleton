<?php

return [

    // JsonRpc连接池
    [
        // 名称
        'name'       => 'jsonRpcPool',
        // 作用域
        'scope'      => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'      => \Mix\JsonRpc\Pool\ConnectionPool::class,
        // 属性注入
        'properties' => [
            // 最多可空闲连接数
            'maxIdle'         => 5,
            // 最大连接数
            'maxActive'       => 50,
            // 拨号器
            'dialer'          => ['ref' => \App\Common\Dialers\JsonRpcDialer::class],
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // JsonRpc连接池拨号
    [
        // 类路径
        'class' => \App\Common\Dialers\JsonRpcDialer::class,
    ],

    // JsonRpc连接
    [
        // 类路径
        'class'           => \Mix\JsonRpc\Connection::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            '127.0.0.1',
            // port
            9506,
            // timeout
            5.0,
        ],
    ],

    // JsonRpc客户端
    [
        // 类路径
        'class'      => \Mix\JsonRpc\Client::class,
        // 属性注入
        'properties' => [
            // 连接池
            'pool' => ['ref' => 'jsonRpcPool'],
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
