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
    public function __construct(ServerRequest $request, Response $response)
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
            /**
             * 闭包内部的同步阻塞代码会在同步服务器进程中执行
             * 代码异常会抛出 InvokeException，即便指定 throw new FooException() 也会转换为 InvokeException
             * 闭包内部代码包含的 Class 文件修改后，需重启 mix-syncd 服务器进程
             */

            /**
             * 直接传输代码的方式
             * 该方式传输数据多，但修改代码无需重启 mix-syncd 服务器进程
             */
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

            /*
             * 也可使用代码中包含 class 的方式
             * 该方式传输数据少，但 class 内部代码修改后需要重启 mix-syncd 服务器进程
             *

            $curl = new \App\Http\Sync\Curl();
            return $curl->exec();

             */
        });
        $conn->release();
        // 响应
        $content = ['code' => 0, 'message' => 'OK', 'data' => $data];
        return ResponseHelper::json($response, $content);
    }

}
