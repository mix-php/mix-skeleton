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
            // 最多可空闲连接数
            'maxIdle'    => 5,
            // 最大连接数
            'maxActive'  => 50,
            // 事件调度器
            'dispatcher' => ['ref' => 'dispatcher'],
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
