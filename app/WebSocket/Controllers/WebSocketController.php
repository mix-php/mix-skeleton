<?php

namespace App\WebSocket\Controllers;

use Mix\Http\Message\Factory\StreamFactory;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Message\Response;
use Mix\WebSocket\Upgrader;

/**
 * Class WebSocketController
 * @package App\WebSocket\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class WebSocketController
{

    /**
     * @var Upgrader
     */
    public $upgrader;

    /**
     * WebSocketController constructor.
     */
    public function __construct()
    {
        $this->upgrader = context()->get(Upgrader::class);
    }

    /**
     * Index
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function index(ServerRequest $request, Response $response)
    {
        try {
            $conn = $this->upgrader->Upgrade($request, $response);
        } catch (\Throwable $e) {
            $response
                ->withBody((new StreamFactory())->createStream('401 Unauthorized'))
                ->withStatus(401);
            return $response;
        }
        xgo(function () use ($conn) {
            call_user_func(new \App\WebSocket\Handlers\WebSocketHandler($conn));
        });
        return $response;
    }

}
