<?php

return [

    // 缓存
    [
        // 名称
        'name'       => 'cache',
        // 作用域
        'scope'      => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'      => \Mix\Cache\Cache::class,
        // 属性注入
        'properties' => [
            // 处理器
            'handler' => ['ref' => \Mix\Cache\FileHandler::class],
        ],
    ],

    // 缓存文件处理器
    [
        // 类路径
        'class'      => \Mix\Cache\FileHandler::class,
        // 属性注入
        'properties' => [
            // 缓存目录
            'dir'        => realpath(__DIR__ . '/../../runtime') . '/cache',
            // 分区
            'partitions' => 64,
        ],
    ],

    // 缓存Redis处理器
    [
        // 类路径
        'class'      => \Mix\Cache\RedisHandler::class,
        // 属性注入
        'properties' => [
            // 连接池
            'pool'      => ['ref' => 'redisPool'],
            // Key前缀
            'keyPrefix' => 'CACHE:',
        ],
    ],

];
