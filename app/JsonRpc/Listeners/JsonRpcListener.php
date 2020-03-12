<?php

namespace App\JsonRpc\Listeners;

use Mix\Event\ListenerInterface;
use Mix\JsonRpc\Event\ProcessedEvent;
use Psr\Log\LoggerInterface;

/**
 * Class JsonRpcListener
 * @package App\JsonRpc\Listeners
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcListener implements ListenerInterface
{

    /**
     * @var LoggerInterface
     */
    public $log;

    /**
     * JsonRpcListener constructor.
     */
    public function __construct()
    {
        $this->log = context()->get('log');
    }

    /**
     * 监听的事件
     * @return array
     */
    public function events(): array
    {
        // 要监听的事件数组，可监听多个事件
        return [
            ProcessedEvent::class,
        ];
    }

    /**
     * 处理事件
     * @param object $event
     */
    public function process(object $event)
    {
        // 事件触发后，会执行该方法
        if (!$event instanceof ProcessedEvent) {
            return;
        }
        $level   = $event->error ? 'warning' : 'info';
        $message = '{time}|{method}|{error}';
        $context = [
            'time'   => $event->time,
            'method' => $event->method,
            'error'  => $event->error,
        ];
        $this->log->log($level, $message, $context);
    }

}
