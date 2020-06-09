<?php

namespace App\Web\Route;

use App\Common\Helpers\ResponseHelper;
use Mix\Http\Message\Response;

/**
 * Class Router
 * @package App\Web\Route
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
        $response = ResponseHelper::view($response, 'errors.not_found', [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'type'    => get_class($exception),
        ]);
        return $response->withStatus(404);
    }

    /**
     * 500 处理
     * @param \Throwable $exception
     * @param Response $response
     * @return Response
     */
    public function error500(\Throwable $exception, Response $response): Response
    {
        $response = ResponseHelper::view($response, 'errors.internal_server_error', [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'type'    => get_class($exception),
        ]);
        return $response->withStatus(500);
    }

}
