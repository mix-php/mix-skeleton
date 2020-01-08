<?php

/**
 * 用户助手函数
 */


/**
 * 获取全局配置对象
 * @return \Noodlehaus\Config
 */
function config()
{
    return $GLOBALS['config'];
}

/**
 * 获取全局App对象
 * @return \Mix\Console\Application
 */
if (!function_exists('app')) {
    function app()
    {
        return $GLOBALS['app'];
    }
}

/**
 * 获取全局上下文对象
 * @return \Mix\Bean\ApplicationContext
 */
if (!function_exists('context')) {
    function context()
    {
        return app()->context;
    }
}
