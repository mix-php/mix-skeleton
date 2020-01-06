<?php

/**
 * 用户助手函数
 */

/**
 * 获取全局配置对象
 * @return Noodlehaus\Config
 * @throws Exception
 */
function config()
{
    if (!isset($GLOBALS['config'])) {
        throw new \Exception('Global $config not found');
    }
    if (!($GLOBALS['config'] instanceof Noodlehaus\Config)) {
        throw new \Exception('Global $config type not match Noodlehaus\Config::class');
    }
    return $GLOBALS['config'];
}
