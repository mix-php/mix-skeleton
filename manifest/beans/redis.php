<?php

return [

    // Redis
    [
        // 名称
        'name'       => 'redis',
        // 作用域
        'scope'      => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'      => \Mix\Redis\Redis::class,
        // 初始方法
        'initMethod' => 'init',
        // 属性注入
        'properties' => [
            // 主机
            'host'          => getenv('REDIS_HOST'),
            // 端口
            'port'          => getenv('REDIS_PORT'),
            // 数据库
            'database'      => getenv('REDIS_DATABASE'),
            // 密码
            'password'      => getenv('REDIS_PASSWORD'),
            // 超时
            'timeout'       => 5,
            // 重连间隔
            'retryInterval' => 0,
            // 读取超时
            'readTimeout'   => -1,
            // 最多可空闲连接数
            'maxIdle'       => 5,
            // 最大连接数
            'maxActive'     => 50,
            // 事件调度器
            'dispatcher'    => ['ref' => 'event'],
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
