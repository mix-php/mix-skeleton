<?php

return [

    // Guzzle
    [
        // 名称
        'name'       => 'guzzle',
        // 类路径
        'class'      => \GuzzleHttp\Client::class,
        // 属性注入
        'properties' => [
            // handler
            'handler' => ['ref' => Mix\Guzzle\Handler\StreamHandler::class],
        ],
    ],

    // Handler
    [
        // 类路径
        'class' => Mix\Guzzle\Handler\StreamHandler::class,
    ],

];
