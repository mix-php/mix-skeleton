<?php

return [

    // Redis连接池
    [
        // 名称
        'name'       => 'redisPool',
        // 作用域
        'scope'      => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'      => \Mix\Redis\Pool\ConnectionPool::class,
        // 属性注入
        'properties' => [
            // 最多可空闲连接数
            'maxIdle'         => 5,
            // 最大连接数
            'maxActive'       => 50,
            // 拨号器
            'dialer'          => ['ref' => \Mix\Redis\Pool\Dialer::class],
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // Redis连接池拨号器
    [
        // 类路径
        'class'      => \Mix\Redis\Pool\Dialer::class,
        // 属性注入
        'properties' => [
            // 主机
            'host'            => getenv('REDIS_HOST'),
            // 端口
            'port'            => getenv('REDIS_PORT'),
            // 数据库
            'database'        => getenv('REDIS_DATABASE'),
            // 密码
            'password'        => getenv('REDIS_PASSWORD'),
            // 超时
            'timeout'         => 5,
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // Redis连接
    [
        // 类路径
        'class'      => \Mix\Redis\Connection::class,
        // 初始方法
        'initMethod' => 'connect',
        // 属性注入
        'properties' => [
            // 主机
            'host'            => getenv('REDIS_HOST'),
            // 端口
            'port'            => getenv('REDIS_PORT'),
            // 数据库
            'database'        => getenv('REDIS_DATABASE'),
            // 密码
            'password'        => getenv('REDIS_PASSWORD'),
            // 超时
            'timeout'         => 5,
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // Redis订阅器
    [
        // 类路径
        'class'           => \Mix\Redis\Subscribe\Subscriber::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            getenv('REDIS_HOST'),
            // port
            getenv('REDIS_PORT'),
            // password
            getenv('REDIS_PASSWORD'),
            // timeout
            5,
        ],
    ],

];
