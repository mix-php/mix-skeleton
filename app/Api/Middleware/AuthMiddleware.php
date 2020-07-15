<?php

namespace App\Api\Middleware;

use App\Common\Helpers\ResponseHelper;
use Mix\Auth\Authorization;
use Mix\Auth\BearerTokenExtractor;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Server\Middleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthMiddleware
 * @package App\Api\Middleware
 * @author liu,jian <coder.keda@gmail.com>
 */
class AuthMiddleware implements MiddlewareInterface
{

    /**
     * @var ServerRequest
     */
    public $request;

    /**
     * @var Response
     */
    public $response;

    /**
     * @var Authorization
     */
    public $auth;

    /**
     * SessionMiddleware constructor.
     * @param ServerRequest $request
     * @param Response $response
     */
    public function __construct(ServerRequest $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->auth     = context()->get('auth');
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 权限验证
        $tokenExtractor = new BearerTokenExtractor($request);
        try {
            $payload = $this->auth->getPayload($tokenExtractor);
        } catch (\Throwable $e) {
            // 中断执行，返回错误信息
            $content  = ['code' => 100001, 'message' => 'No access'];
            $response = ResponseHelper::json($this->response, $content);
            return $response;
        }

        // 把 JWT Payload 放入 Request 的上下文，方便其他位置调用
        $context = $this->request->getContext();
        $context->withValue('payload', $payload);

        // 继续往下执行
        return $handler->handle($request);
    }

}
