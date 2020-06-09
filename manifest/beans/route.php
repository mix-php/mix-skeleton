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
            // routeDefinitionCallback
            function (Mix\FastRoute\RouteCollector $collector) {
                $collector->post('/file/upload',
                    [\App\Api\Controllers\FileController::class, 'upload']
                    [\App\Api\Middleware\ActionMiddleware::class]
                );
                $collector->get('/curl',
                    [\App\Api\Controllers\CurlController::class, 'index']
                    [\App\Api\Middleware\ActionMiddleware::class]
                );
                $collector->group('/v2',
                    function (Mix\FastRoute\RouteCollector $collector) {
                        $collector->post('/user/create',
                            [\App\Api\Controllers\UserController::class, 'create'],
                            [\App\Api\Middleware\ActionMiddleware::class]
                        );
                    },
                    [\App\Api\Middleware\GroupMiddleware::class]
                );
            },
            // middleware
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
            // routeDefinitionCallback
            function (Mix\FastRoute\RouteCollector $collector) {
                $collector->get('/',
                    [\App\Web\Controllers\IndexController::class, 'index']
                    [\App\Web\Middleware\ActionMiddleware::class]
                );
                $collector->get('/profile/{id:\d+}',
                    [\App\Web\Controllers\ProfileController::class, 'index']
                    [\App\Web\Middleware\ActionMiddleware::class]
                );
            },
            // middleware
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
            // routeDefinitionCallback
            function (Mix\FastRoute\RouteCollector $collector) {
                $collector->get('/websocket',
                    [\App\WebSocket\Controllers\WebSocketController::class, 'index']
                    [\App\Web\Middleware\ActionMiddleware::class]
                );
            },
            // middleware
            [\App\Web\Middleware\GlobalMiddleware::class],
        ],
    ],

];
