<?php

namespace App\Common\Dialers;

use Mix\Pool\DialerInterface;
use Mix\JsonRpc\Connection;

/**
 * Class JsonRpcDialer
 * @package App\Common\Dialers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcDialer implements DialerInterface
{

    /**
     * 拨号
     * @return Connection
     */
    public function dial()
    {
        // 创建一个连接并返回
        return context()->get(Connection::class);
    }

}
