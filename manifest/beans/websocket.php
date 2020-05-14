<?php

return [

    // WebSocket升级器
    [
        // 作用域
        'scope' => \Mix\Bean\BeanDefinition::SINGLETON,
        // 类路径
        'class' => \Mix\WebSocket\Upgrader::class,
    ],

];
