<?php

return [

    // Redis
    [
        // 名称
        'name'            => 'redis',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Redis\Redis::class,
        // 构造函数注入
        'constructorArgs' => [
            // host
            getenv('REDIS_HOST'),
            // port
            getenv('REDIS_PORT'),
            // password
            getenv('REDIS_PASSWORD'),
            // database
            getenv('REDIS_DATABASE'),
            // timeout
            5,
            // retryInterval
            0,
            // readTimeout
            -1,
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
            'dispatcher' => ['ref' => 'eventDispatcher'],
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
