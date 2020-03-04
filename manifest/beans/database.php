<?php

return [

    // Database连接池
    [
        // 名称
        'name'       => 'dbPool',
        // 作用域
        'scope'      => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'      => \Mix\Database\Pool\ConnectionPool::class,
        // 属性注入
        'properties' => [
            // 最多可空闲连接数
            'maxIdle'         => 5,
            // 最大连接数
            'maxActive'       => 50,
            // 拨号器
            'dialer'          => ['ref' => \Mix\Database\Pool\Dialer::class],
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // Database连接池拨号器
    [
        // 类路径
        'class'      => \Mix\Database\Pool\Dialer::class,
        // 属性注入
        'properties' => [
            // 数据源格式
            'dsn'             => getenv('DATABASE_DSN'),
            // 数据库用户名
            'username'        => getenv('DATABASE_USERNAME'),
            // 数据库密码
            'password'        => getenv('DATABASE_PASSWORD'),
            // 驱动连接选项: http://php.net/manual/zh/pdo.setattribute.php
            'attributes'      => [
                // 设置默认的提取模式: \PDO::FETCH_OBJ | \PDO::FETCH_ASSOC
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                // 超时
                \PDO::ATTR_TIMEOUT            => 5,
            ],
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

    // Database连接
    [
        // 类路径
        'class'      => \Mix\Database\Connection::class,
        // 初始方法
        'initMethod' => 'connect',
        // 属性注入
        'properties' => [
            // 数据源格式
            'dsn'             => getenv('DATABASE_DSN'),
            // 数据库用户名
            'username'        => getenv('DATABASE_USERNAME'),
            // 数据库密码
            'password'        => getenv('DATABASE_PASSWORD'),
            // 驱动连接选项: http://php.net/manual/zh/pdo.setattribute.php
            'attributes'      => [
                // 设置默认的提取模式: \PDO::FETCH_OBJ | \PDO::FETCH_ASSOC
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                // 超时
                \PDO::ATTR_TIMEOUT            => 5,
            ],
            // 事件调度器
            'eventDispatcher' => ['ref' => 'event'],
        ],
    ],

];
