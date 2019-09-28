<?php

namespace App\Udp\Helpers;

use Swoole\Coroutine\Channel;

/**
 * Class SendHelper
 * @package App\Udp\Helpers
 * @author liu,jian <coder.keda@gmail.com>
 */
class SendHelper
{

    /**
     * Send error
     * @param Channel $sendChan
     * @param array $peer
     * @param int $code
     * @param string $message
     * @param null $id
     */
    public static function error(Channel $sendChan, array $peer, int $code, string $message, $id = null)
    {
        $data = JsonRpcHelper::error($code, $message, $id);
        $sendChan->push([$data, $peer]);
    }

    /**
     * Send result
     * @param Channel $sendChan
     * @param array $peer
     * @param $result
     * @param null $id
     */
    public static function result(Channel $sendChan, array $peer, $result, $id = null)
    {
        $data = JsonRpcHelper::result($result, $id);
        $sendChan->push([$data, $peer]);
    }

}
