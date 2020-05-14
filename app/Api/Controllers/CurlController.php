<?php

namespace App\Api\Controllers;

use App\Common\Helpers\ResponseHelper;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\SyncInvoke\Client\Client;

/**
 * Class CurlController
 * @package App\Api\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class CurlController
{

    /**
     * @var Client
     */
    public $client;

    /**
     * CurlController constructor.
     */
    public function __construct()
    {
        $this->client = context()->get(Client::class);
    }

    /**
     * Index
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     * @throws \Mix\SyncInvoke\Exception\InvokeException
     * @throws \Swoole\Exception
     */
    public function index(ServerRequest $request, Response $response)
    {
        // 跨进程执行同步代码
        $data = $this->client->invoke(function () {
            /**
             * 闭包内部的同步阻塞代码会在同步服务器进程中执行
             * 代码异常会抛出 InvokeException，即便指定 throw new FooException() 也会转换为 InvokeException
             * 闭包内部代码包含的 Class 文件修改后，需重启 mix-syncinvoke 服务器进程
             */

            /**
             * 直接传输代码的方式
             * 该方式传输数据多，但修改代码无需重启 mix-syncinvoke 服务器进程
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
             * 该方式传输数据少，但 class 内部代码修改后需要重启 mix-syncinvoke 服务器进程
             *

            $curl = new \App\Api\SyncInvoke\Curl();
            return $curl->exec();

             */
        });
        // 响应
        $content = ['code' => 0, 'message' => 'OK', 'data' => $data];
        return ResponseHelper::json($response, $content);
    }

}
