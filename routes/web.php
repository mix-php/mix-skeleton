<?php

return function (Mix\FastRoute\RouteCollector $collector) {
    $collector->any('/',
        [\App\Web\Controllers\IndexController::class, 'index'],
        [\App\Web\Middleware\ActionMiddleware::class]
    );

    $collector->get('/profile/{id:\d+}',
        [\App\Web\Controllers\ProfileController::class, 'index'],
        [\App\Web\Middleware\ActionMiddleware::class]
    );
};
