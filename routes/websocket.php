<?php

return function (Mix\FastRoute\RouteCollector $collector) {
    $collector->get('/websocket',
        [\App\WebSocket\Controllers\WebSocketController::class, 'index'],
        [\App\Web\Middleware\ActionMiddleware::class]
    );
};
