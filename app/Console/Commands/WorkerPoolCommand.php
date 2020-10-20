<?php

namespace App\Console\Commands;

use App\Console\Workers\FooWorker;
use Mix\WorkerPool\WorkerDispatcher;
use Swoole\Coroutine\Channel;

/**
 * Class WorkerPoolCommand
 * @package App\Console\Commands
 */
class WorkerPoolCommand
{

    /**
     * 主函数
     */
    public function main()
    {
        $maxWorkers = 20;
        $maxQueue   = 10;
        $jobQueue   = new Channel($maxQueue);
        $dispatcher = new WorkerDispatcher($jobQueue, $maxWorkers, FooWorker::class);

        xgo(function () use ($jobQueue, $dispatcher) {
            // 投放任务
            for ($i = 0; $i < 1000; $i++) {
                $jobQueue->push($i);
            }
            // 停止
            $dispatcher->stop();
        });

        $dispatcher->run(); // 阻塞代码，直到任务全部执行完成并且全部 Worker 停止
    }

}
