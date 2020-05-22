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
            // 最多可空闲连接数
            'maxIdle'    => 5,
            // 最大连接数
            'maxActive'  => 50,
            // 事件调度器
            'dispatcher' => ['ref' => 'dispatcher'],
        ],
    ],

];
