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
     * @param \Exception $exception
     * @param Response $response
     */
    public function show404(\Exception $exception, Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.not_found', [
            'message' => $exception->getMessage(),
        ]);
        $response->withStatus(404)->end();
    }

    /**
     * 500 处理
     * @param \Exception $exception
     * @param Response $response
     */
    public function show500(\Exception $exception, Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.internal_server_error', [
            'message' => $exception->getMessage(),
            'type'    => get_class($exception),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTraceAsString(),
        ]);
        $response->withStatus(500)->end();
    }

}
