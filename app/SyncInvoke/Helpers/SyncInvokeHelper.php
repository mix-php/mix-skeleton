<?php

namespace App\SyncInvoke\Helpers;

use Mix\SyncInvoke\Pool\ConnectionPool;

/**
 * Class SyncInvokeHelper
 * @package App\SyncInvoke\Helpers
 * @author liu,jian <coder.keda@gmail.com>
 */
class SyncInvokeHelper
{

    /**
     * 执行同步代码
     * 跨进程在同步执行服务器中执行
     * @param ConnectionPool $pool
     * @param \Closure $closure
     * @return mixed
     * @throws \Mix\SyncInvoke\Exception\InvokeException
     * @throws \Swoole\Exception
     */
    public static function invoke(ConnectionPool $pool, \Closure $closure)
    {
        $conn = $pool->getConnection();
        $data = $conn->invoke($closure);
        $conn->release();
        return $data;
    }

}
