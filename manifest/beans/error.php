<?php

return [

    // 错误
    [
        // 名称
        'name'            => 'error',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Console\Error::class,
        // 构造函数注入
        'constructorArgs' => [
            // 错误级别
            E_ALL,
            // 日志
            ['ref' => 'logger'],
        ],
        // 属性注入
        'properties'      => [
            // 事件调度器
            'dispatcher' => ['ref' => 'eventDispatcher'],
        ],
    ],

];
