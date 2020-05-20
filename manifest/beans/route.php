<?php

return [

    // Api路由
    [
        // 名称
        'name'       => 'apiRouter',
        // 类路径
        'class'      => \App\Api\Route\Router::class,
        // 初始方法
        'initMethod' => 'parse',
        // 属性注入
        'properties' => [
            // 默认变量规则
            'defaultPattern' => '[\w-]+',
            // 路由变量规则
            'patterns'       => [
            ],
            // 全局中间件
            'middleware'     => [\App\Api\Middleware\GlobalMiddleware::class],
            // 路由规则
            'rules'          => [
                // 普通路由
                'POST /file/upload' => [[\App\Api\Controllers\FileController::class, 'upload'], 'middleware' => [\App\Api\Middleware\ActionMiddleware::class]],
                '/curl'             => [[\App\Api\Controllers\CurlController::class, 'index'], 'middleware' => [\App\Api\Middleware\ActionMiddleware::class]],
                // 分组路由
                '/v2'               => [
                    // 分组路由规则
                    [
                        // 分组路由
                        'POST /user/create' => [[\App\Api\Controllers\UserController::class, 'create'], 'middleware' => [\App\Api\Middleware\ActionMiddleware::class]],
                    ],
                    // 分组中间件
                    'middleware' => [\App\Api\Middleware\GroupMiddleware::class],
                ],
            ],
        ],
    ],

    // Web路由
    [
        // 名称
        'name'       => 'webRouter',
        // 类路径
        'class'      => \App\Web\Route\Router::class,
        // 初始方法
        'initMethod' => 'parse',
        // 属性注入
        'properties' => [
            // 默认变量规则
            'defaultPattern' => '[\w-]+',
            // 路由变量规则
            'patterns'       => [
                'id' => '\d+',
            ],
            // 全局中间件
            'middleware'     => [\App\Web\Middleware\GlobalMiddleware::class],
            // 路由规则
            'rules'          => [
                // 普通路由
                '/'             => [[\App\Web\Controllers\IndexController::class, 'index'], 'middleware' => [\App\Web\Middleware\ActionMiddleware::class]],
                '/profile/{id}' => [[\App\Web\Controllers\ProfileController::class, 'index'], 'middleware' => [\App\Web\Middleware\ActionMiddleware::class]],
            ],
        ],
    ],

    // WebSocket路由
    [
        // 名称
        'name'       => 'webSocketRouter',
        // 类路径
        'class'      => \App\Web\Route\Router::class,
        // 初始方法
        'initMethod' => 'parse',
        // 属性注入
        'properties' => [
            // 默认变量规则
            'defaultPattern' => '[\w-]+',
            // 路由变量规则
            'patterns'       => [
            ],
            // 全局中间件
            'middleware'     => [\App\Web\Middleware\GlobalMiddleware::class],
            // 路由规则
            'rules'          => [
                // 普通路由
                '/websocket' => [[\App\WebSocket\Controllers\WebSocketController::class, 'index'], 'middleware' => [\App\Web\Middleware\ActionMiddleware::class]],
            ],
        ],
    ],

];
