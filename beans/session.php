<?php

return [

    // Session
    [
        // 名称
        'name'       => 'session',
        // 类路径
        'class'      => \Mix\Session\Session::class,
        // 初始方法
        'initMethod' => 'start',
        // 属性注入
        'properties' => [
            // 处理器
            'handler'        => ['ref' => \Mix\Session\RedisHandler::class],
            // session键名
            'name'           => 'session_id',
            // 生存时间
            'maxLifetime'    => 7200,
            // 过期时间
            'cookieExpires'  => 0,
            // 有效的服务器路径
            'cookiePath'     => '/',
            // 有效域名/子域名
            'cookieDomain'   => '',
            // 仅通过安全的 HTTPS 连接传给客户端
            'cookieSecure'   => false,
            // 仅可通过 HTTP 协议访问
            'cookieHttpOnly' => false,
        ],
    ],

    // Session Redis 处理器
    [
        // 类路径
        'class'      => \Mix\Session\RedisHandler::class,
        // 属性注入
        'properties' => [
            // 连接池
            'pool'      => ['ref' => 'redisPool'],
            // Key前缀
            'keyPrefix' => 'SESSION:',
        ],
    ],

];
