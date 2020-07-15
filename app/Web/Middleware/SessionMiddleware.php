<?php

namespace App\Web\Middleware;

use App\Common\Helpers\ResponseHelper;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Server\Middleware\MiddlewareInterface;
use Mix\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class SessionMiddleware
 * @package App\Web\Middleware
 * @author liu,jian <coder.keda@gmail.com>
 */
class SessionMiddleware implements MiddlewareInterface
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
     * @var Session
     */
    public $session;

    /**
     * SessionMiddleware constructor.
     * @param ServerRequest $request
     * @param Response $response
     */
    public function __construct(ServerRequest $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->session  = context()->get('session');
        $this->session->start($request, $response);

        // 把 Session 放入 Request 的上下文，方便其他位置调用
        $context = $this->request->getContext();
        $context->withValue('session', $this->session);
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
        // 会话验证
        $payload = $this->session->get('payload');
        if (!$payload) {
            // 中断执行，返回错误信息
            $content  = ['code' => 100001, 'message' => 'No access'];
            $response = ResponseHelper::json($this->response, $content);
            return $response;
        }

        // 继续往下执行
        return $handler->handle($request);
    }

}
