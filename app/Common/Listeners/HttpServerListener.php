<?php

namespace App\Common\Listeners;

use Mix\Event\ListenerInterface;
use Mix\Http\Server\Event\HandledEvent;
use Psr\Log\LoggerInterface;

/**
 * Class HttpServerListener
 * @package App\Common\Listeners
 */
class HttpServerListener implements ListenerInterface
{

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * JsonRpcListener constructor.
     */
    public function __construct()
    {
        $this->logger = context()->get('logger');
    }

    /**
     * 监听的事件
     * @return array
     */
    public function events(): array
    {
        // 要监听的事件数组，可监听多个事件
        return [
            HandledEvent::class,
        ];
    }

    /**
     * 处理事件
     * @param object $event
     */
    public function process(object $event)
    {
        // 事件触发后，会执行该方法
        if (!$event instanceof HandledEvent) {
            return;
        }
        $level   = $event->error ? 'warning' : 'info';
        $message = '{time}|{method}|{url}|{status}|{error}';
        $context = [
            'time'   => $event->time,
            'method' => $event->request->getMethod(),
            'url'    => $event->request->getUri()->__toString(),
            'status' => $event->response->getStatusCode(),
            'error'  => $event->error,
        ];
        $this->logger->log($level, $message, $context);
    }

}
