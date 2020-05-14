<?php

return [

    // 缓存
    [
        // 名称
        'name'            => 'cache',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Cache\Cache::class,
        // 构造函数注入
        'constructorArgs' => [
            // 处理器
            ['ref' => \Mix\Cache\FileHandler::class],
        ],
    ],

    // 缓存文件处理器
    [
        // 类路径
        'class'           => \Mix\Cache\FileHandler::class,
        // 构造函数注入
        'constructorArgs' => [
            // 缓存目录
            realpath(__DIR__ . '/../../runtime') . '/cache',
            // 分区
            64,
        ],
    ],

    // 缓存Redis处理器
    [
        // 类路径
        'class'           => \Mix\Cache\RedisHandler::class,
        // 构造函数注入
        'constructorArgs' => [
            // redis
            ['ref' => 'redis'],
            // Key前缀
            'CACHE:',
        ],
    ],

];
