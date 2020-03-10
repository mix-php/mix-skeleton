<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ResponseHelper;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\JsonRpc\Client\Connection;
use Mix\JsonRpc\Factory\RequestFactory;

/**
 * Class JsonRpcController
 * @package App\Http\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcController
{

    /**
     * @var Connection
     */
    public $conn;

    /**
     * CurlController constructor.
     */
    public function __construct(ServerRequest $request, Response $response)
    {
        $this->conn = context()->get(Connection::class);
    }

    /**
     * Index
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     * @throws \Mix\JsonRpc\Exception\ParseException
     * @throws \Swoole\Exception
     */
    public function index(ServerRequest $request, Response $response)
    {
        $a = $request->getAttribute('a', 0);
        $b = $request->getAttribute('b', 0);
        // 调用rpc
        $rpcRequest  = (new RequestFactory)->createRequest('Foo.Sum', [$a, $b], 10001);
        $rpcResponse = $this->conn->call($rpcRequest);
        if ($rpcResponse->error) {
            throw new \Exception(sprintf('rpc call failed: %s', $rpcResponse->error->message), $rpcResponse->error->code);
        }
        // 响应
        $content = ['code' => 0, 'message' => 'OK', 'data' => $rpcResponse->result];
        return ResponseHelper::json($response, $content);
    }

}
