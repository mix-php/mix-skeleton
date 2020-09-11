<?php

return [

    // Api路由
    [
        // 名称
        'name'            => 'apiRouter',
        // 类路径
        'class'           => \App\Api\Route\Router::class,
        // 构造函数注入
        'constructorArgs' => [
            // routeDefinition
            __DIR__ . '/../../routes/api.php',
            // global middleware
            [\App\Api\Middleware\GlobalMiddleware::class],
        ],
    ],

    // Web路由
    [
        // 名称
        'name'            => 'webRouter',
        // 类路径
        'class'           => \App\Web\Route\Router::class,
        // 构造函数注入
        'constructorArgs' => [
            // routeDefinition
            __DIR__ . '/../../routes/web.php',
            // global middleware
            [\App\Web\Middleware\GlobalMiddleware::class],
        ],
    ],

    // WebSocket路由
    [
        // 名称
        'name'            => 'webSocketRouter',
        // 类路径
        'class'           => \App\Web\Route\Router::class,
        // 构造函数注入
        'constructorArgs' => [
            // routeDefinition
            __DIR__ . '/../../routes/websocket.php',
            // global middleware
            [\App\Web\Middleware\GlobalMiddleware::class],
        ],
    ],

];
