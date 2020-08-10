<?php

namespace App\Common\Listeners;

use Mix\Console\CommandLine\Flag;
use Mix\Console\Event\CommandBeforeExecuteEvent;
use Mix\Event\ListenerInterface;
use Mix\Console\Process;

/**
 * Class CommandListener
 * @package App\Common\Listeners
 * @author liu,jian <coder.keda@gmail.com>
 */
class CommandListener implements ListenerInterface
{

    /**
     * 监听的事件
     * @return array
     */
    public function events(): array
    {
        // 要监听的事件数组，可监听多个事件
        return [
            CommandBeforeExecuteEvent::class,
        ];
    }

    /**
     * 处理事件
     * @param object $event
     * @throws \Swoole\Exception
     */
    public function process(object $event)
    {
        // 事件触发后，会执行该方法
        // 守护处理
        if ($event instanceof CommandBeforeExecuteEvent) {
            if (Flag::bool(['d', 'daemon'], false)) {
                Process::daemon();
            }
        }
    }

}
