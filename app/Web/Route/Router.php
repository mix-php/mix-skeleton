<?php

namespace App\Web\Route;

use App\Common\Helpers\ResponseHelper;
use Mix\Http\Message\Response;

/**
 * Class Router
 * @package App\Web\Route
 */
class Router extends \Mix\Route\Router
{

    /**
     * 404 处理
     * @param \Throwable $exception
     * @param Response $response
     */
    public function show404(\Throwable $exception, Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.not_found', [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'type'    => get_class($exception),
        ]);
        $response->withStatus(404)->send();
    }

    /**
     * 500 处理
     * @param \Throwable $exception
     * @param Response $response
     */
    public function show500(\Throwable $exception, Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.internal_server_error', [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'type'    => get_class($exception),
        ]);
        $response->withStatus(500)->send();
    }

}
