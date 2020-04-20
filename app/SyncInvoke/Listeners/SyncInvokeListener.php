<?php

namespace App\SyncInvoke\Listeners;

use Mix\Event\ListenerInterface;
use Mix\SyncInvoke\Event\InvokedEvent;
use Psr\Log\LoggerInterface;

/**
 * Class SyncInvokeListener
 * @package App\SyncInvoke\Listeners
 * @author liu,jian <coder.keda@gmail.com>
 */
class SyncInvokeListener implements ListenerInterface
{

    /**
     * @var LoggerInterface
     */
    public $log;

    /**
     * SyncInvokeListener constructor.
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
        $this->log->log($level, $message, $context);
    }

}
