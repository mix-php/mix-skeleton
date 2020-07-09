<?php

namespace App\Console\Commands;

use App\Console\Workers\FooWorker;
use Mix\WorkerPool\WorkerPoolDispatcher;
use Swoole\Coroutine\Channel;

/**
 * Class WorkerPoolCommand
 * @package App\Console\Commands
 */
class WorkerPoolCommand
{

    /**
     * 主函数
     * @throws \PhpDocReader\AnnotationException
     * @throws \ReflectionException
     */
    public function main()
    {
        $maxWorkers = 20;
        $maxQueue   = 10;
        $jobQueue   = new Channel($maxQueue);
        $dispatcher = new WorkerPoolDispatcher($jobQueue, $maxWorkers);
        $dispatcher->start(FooWorker::class);

        // 投放任务
        for ($i = 0; $i < 1000; $i++) {
            $jobQueue->push($i);
        }

        // 停止
        $dispatcher->stop();
    }

}
