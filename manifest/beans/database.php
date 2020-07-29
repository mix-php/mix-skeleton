<?php

return [

    // Database
    [
        // 名称
        'name'            => 'database',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Database\Database::class,
        // 构造函数注入
        'constructorArgs' => [
            // dsn
            getenv('DATABASE_DSN'),
            // username
            getenv('DATABASE_USERNAME'),
            // password
            getenv('DATABASE_PASSWORD'),
            // options: http://php.net/manual/zh/pdo.setattribute.php
            [
                // 设置默认的提取模式: \PDO::FETCH_OBJ | \PDO::FETCH_ASSOC
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                // 超时
                \PDO::ATTR_TIMEOUT            => 5,
            ],
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

];
