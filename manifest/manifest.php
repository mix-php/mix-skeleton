<?php

// 应用清单
return [

    // 应用名称
    'appName'     => 'mix-app',

    // 应用版本
    'appVersion'  => '0.0.0',

    // 应用调试
    'appDebug'    => getenv('APP_DEBUG'),

    // 基础路径
    'basePath'    => realpath(__DIR__ . '/../'),

    // 依赖路径
    'beanPath'    => __DIR__ . '/beans',

    // 命令路径
    'commandPath' => __DIR__ . '/commands',

    // 协程配置
    'coroutine'   => [
        // 启用
        'enable'  => true,
        // 选项
        'options' => [
            'max_coroutine' => 300000,
            'hook_flags'    => SWOOLE_HOOK_ALL,
        ],
    ],

];
