<?php

namespace App\Api\Route;

use Mix\Http\Message\Factory\StreamFactory;
use Mix\Http\Message\Response;

/**
 * Class Router
 * @package App\Api\Route
 */
class Router extends \Mix\Route\Router
{

    /**
     * 404 处理
     * @param Response $response
     */
    public static function show404(Response $response)
    {
        $content = '404 Not Found';
        $body    = (new StreamFactory())->createStream($content);
        return $response
            ->withContentType('text/plain')
            ->withBody($body)
            ->withStatus(404)
            ->end();
    }

    /**
     * 500 处理
     * @param \Throwable $e
     * @param Response $response
     */
    public static function show500(\Throwable $e, Response $response)
    {
        $content = [
            'error' => [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'type'    => get_class($e),
            ],
        ];
        $body    = (new StreamFactory())->createStream(json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response
            ->withContentType('application/json', 'utf-8')
            ->withBody($body)
            ->withStatus(500)
            ->end();
    }

}
