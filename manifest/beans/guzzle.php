<?php

return [

    // Guzzle
    [
        // 名称
        'name'            => 'guzzle',
        // 类路径
        'class'           => \GuzzleHttp\Client::class,
        // 构造函数注入
        'constructorArgs' => [
            // config
            [
                'handler' => ['ref' => Mix\Guzzle\Handler\StreamHandler::class],
            ],
        ],
    ],

    // Handler
    [
        // 类路径
        'class' => Mix\Guzzle\Handler\StreamHandler::class,
    ],

];
