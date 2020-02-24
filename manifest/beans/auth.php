<?php

return [

    // 授权
    [
        // 名称
        'name'            => 'auth',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Auth\Authorization::class,
        // 构造函数注入
        'constructorArgs' => [
            // JWT
            ['ref' => \Mix\Auth\JWT::class],
        ],
    ],

    // JWT
    [
        // 类路径
        'class'      => \Mix\Auth\JWT::class,
        // 属性注入
        'properties' => [
            // 钥匙
            'key'       => 'example_key',
            // 签名算法
            'algorithm' => \Mix\Auth\JWT::ALGORITHM_HS256,
        ],
    ],

];
