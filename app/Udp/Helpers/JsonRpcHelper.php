<?php

namespace App\Udp\Helpers;

use Mix\Helper\JsonHelper;

/**
 * Class JsonRpcHelper
 * @package App\Udp\Helpers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcHelper
{

    /**
     * Error
     * @param $code
     * @param $message
     * @param null $id
     * @return string
     */
    public static function error(int $code, string $message, $id = null)
    {
        $data = [
            'jsonrpc' => '2.0',
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
            'id'      => $id,
        ];
        return JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Result
     * @param $result
     * @param null $id
     * @return string
     */
    public static function result($result, $id = null)
    {
        $data = [
            'jsonrpc' => '2.0',
            'error'   => null,
            'result'  => $result,
            'id'      => $id,
        ];
        return JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
    }

}
