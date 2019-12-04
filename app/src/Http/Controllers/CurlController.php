<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelper;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\Sync\Invoke\Pool\ConnectionPool;

/**
 * Class CurlController
 * @package App\Http\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class CurlController
{

    /**
     * @var ConnectionPool
     */
    public $pool;

    /**
     * CurlController constructor.
     */
    public function __construct()
    {
        $this->pool = context()->get('syncInvokePool');
    }

    /**
     * Index
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     * @throws \Mix\Sync\Invoke\Exception\InvokeException
     * @throws \Swoole\Exception
     */
    public function index(ServerRequest $request, Response $response)
    {
        // 跨进程执行同步代码
        $conn = $this->pool->getConnection();
        $data = $conn->invoke(function () {
            // 执行同步阻塞代码
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => "http://ip-api.com/json/?lang=zh-CN",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
            ]);
            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return ['error' => "cURL Error #: " . $err];
            }
            return json_decode($response, true);
        });
        // 响应
        $content = ['code' => 0, 'message' => 'OK', 'data' => $data];
        return ResponseHelper::json($response, $content);
    }

}
