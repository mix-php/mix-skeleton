<?php

namespace App\Console\Commands;

use App\Console\Workers\FooWorker;
use Mix\Redis\Connection;
use Mix\Redis\Redis;
use Mix\Signal\SignalNotify;
use Mix\WorkerPool\WorkerDispatcher;
use Swoole\Coroutine\Channel;

/**
 * Class WorkerPoolDaemonCommand
 * @package App\Console\Commands
 */
class WorkerPoolDaemonCommand
{

    /**
     * @var Channel
     */
    public $quit;

    /**
     * @var Redis
     */
    public $redis;

    /**
     * @var Connection
     */
    public $conn;

    /**
     * CoroutinePoolDaemonCommand constructor.
     */
    public function __construct()
    {
        $this->quit  = new Channel();
        $this->redis = context()->get('redis');
        $this->conn  = $this->redis->borrow();
    }

    /**
     * 主函数
     */
    public function main()
    {
        // 捕获信号
        $notify = new SignalNotify(SIGHUP, SIGINT, SIGTERM);
        xgo(function () use ($notify) {
            $notify->channel()->pop();
            $this->quit->push(true);
            $notify->stop();
        });

        // 协程池执行任务
        $maxWorkers = 20;
        $maxQueue   = 20;
        $jobQueue   = new Channel($maxQueue);
        $dispatcher = new WorkerDispatcher($jobQueue, $maxWorkers, FooWorker::class);

        xgo(function () use ($jobQueue, $dispatcher) {
            // 投放任务
            while (true) {
                if (!$this->quit->isEmpty()) {
                    $dispatcher->stop();
                    return;
                }
                try {
                    $data = $this->conn->brPop(['test'], 1);
                } catch (\Throwable $ex) {
                    println(sprintf('Error: [%d] %s %s', $ex->getCode(), $ex->getMessage(), get_class($ex)));
                    $dispatcher->stop();
                    return;
                }
                if (!$data) {
                    continue;
                }
                $data = array_pop($data); // brPop命令最后一个键才是值
                $jobQueue->push($data);
            }
        });

        $dispatcher->run(); // 阻塞代码，直到任务全部执行完成并且全部 Worker 停止
    }

}
