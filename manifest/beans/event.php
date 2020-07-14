<?php

return [

    // 事件调度器
    [
        // 名称
        'name'            => 'eventDispatcher',
        // 作用域
        'scope'           => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class'           => \Mix\Event\EventDispatcher::class,
        // 构造函数注入
        'constructorArgs' => [
            \App\Common\Listeners\CommandListener::class,
            // \App\Common\Listeners\DatabaseListener::class,
            // \App\Common\Listeners\RedisListener::class,
            // \App\Common\Listeners\SyncInvokeServerListener::class,
            // \App\Common\Listeners\HttpServerListener::class,
        ],
    ],

];
