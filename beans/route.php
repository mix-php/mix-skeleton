<?php

return [

    // 路由
    [
        // 名称
        'name'       => 'route',
        // 类路径
        'class'      => \Mix\Route\Router::class,
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
            'middleware'     => [\App\Http\Middleware\GlobalMiddleware::class],
            // 路由规则
            'rules'          => [
                // 普通路由
                '/'                 => [[\App\Http\Controllers\IndexController::class, 'index'], 'middleware' => [\App\Http\Middleware\ActionMiddleware::class]],
                '/profile/{id}'     => [[\App\Http\Controllers\ProfileController::class, 'index'], 'middleware' => [\App\Http\Middleware\ActionMiddleware::class]],
                'POST /file/upload' => [[\App\Http\Controllers\FileController::class, 'upload'], 'middleware' => [\App\Http\Middleware\ActionMiddleware::class]],
                '/curl'             => [[\App\Http\Controllers\CurlController::class, 'index'], 'middleware' => [\App\Http\Middleware\ActionMiddleware::class]],
                // 分组路由
                '/v2'               => [
                    // 分组中间件
                    'middleware' => [\App\Http\Middleware\GroupMiddleware::class],
                    // 分组路由规则
                    'rules'      => [
                        // 分组路由
                        'POST /user/create' => [[\App\Http\Controllers\UserController::class, 'create'], 'middleware' => [\App\Http\Middleware\ActionMiddleware::class]],
                    ],
                ],
            ],
        ],
    ],

];
