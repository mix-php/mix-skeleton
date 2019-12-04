<?php

namespace App\Common\Dialers;

use Mix\Pool\DialerInterface;
use Mix\Sync\Invoke\Connection;

/**
 * Class InvokeDialer
 * @package App\Common\Dialers
 * @author liu,jian <coder.keda@gmail.com>
 */
class SyncInvokeDialer implements DialerInterface
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
