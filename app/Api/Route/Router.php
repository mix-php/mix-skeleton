<?php

namespace App\Api\Route;

use Mix\Http\Message\Factory\StreamFactory;
use Mix\Http\Message\Response;

/**
 * Class Router
 * @package App\Api\Route
 */
class Router extends \Mix\FastRoute\Router
{

    /**
     * 404 处理
     * @param \Throwable $exception
     * @param Response $response
     * @return Response
     */
    public function error404(\Throwable $exception, Response $response): Response
    {
        $content = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'status'  => '404 Not Found',
        ];
        $body    = (new StreamFactory())->createStream(json_encode($content));
        return $response
            ->withContentType('application/json', 'utf-8')
            ->withBody($body)
            ->withStatus(404);
    }

    /**
     * 500 处理
     * @param \Throwable $exception
     * @param Response $response
     * @return Response
     */
    public function error500(\Throwable $exception, Response $response): Response
    {
        $content = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'status'  => '500 Internal Server Error',
        ];
        $body    = (new StreamFactory())->createStream(json_encode($content));
        return $response
            ->withContentType('application/json', 'utf-8')
            ->withBody($body)
            ->withStatus(500);
    }

}
