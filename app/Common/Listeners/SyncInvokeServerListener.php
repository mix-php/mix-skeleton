<?php

namespace App\Common\Listeners;

use Mix\Event\ListenerInterface;
use Mix\SyncInvoke\Event\InvokedEvent;
use Psr\Log\LoggerInterface;

/**
 * Class SyncInvokeServerListener
 * @package App\Common\Listeners
 */
class SyncInvokeServerListener implements ListenerInterface
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
            InvokedEvent::class,
        ];
    }

    /**
     * 处理事件
     * @param object $event
     */
    public function process(object $event)
    {
        // 事件触发后，会执行该方法
        if (!$event instanceof InvokedEvent) {
            return;
        }
        $level   = $event->error ? 'warning' : 'info';
        $message = '{time}|{raw}|{error}';
        $context = [
            'time'  => $event->time,
            'raw'   => preg_replace('/\s/', '', substr($event->raw, 40, 200)),
            'error' => $event->error,
        ];
        $this->logger->log($level, $message, $context);
    }

}
