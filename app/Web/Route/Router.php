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
     * @param Response $response
     */
    public static function show404(Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.not_found', [
            'message' => '404 Not Found',
        ]);
        $response->withStatus(404)->end();
    }

    /**
     * 500 处理
     * @param \Throwable $e
     * @param Response $response
     */
    public static function show500(\Throwable $e, Response $response)
    {
        $response = ResponseHelper::view($response, 'errors.internal_server_error', [
            'message' => $e->getMessage(),
            'type'    => get_class($e),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);
        $response->withStatus(500)->end();
    }

}
