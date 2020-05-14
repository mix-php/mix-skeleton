<?php

namespace App\Console\Commands;

use App\Console\Workers\CoroutinePoolDaemonWorker;
use Mix\Concurrent\CoroutinePool\Dispatcher;
use Mix\Redis\Redis;
use Swoole\Coroutine\Channel;
use Mix\Helper\ProcessHelper;

/**
 * Class CoroutinePoolDaemonCommand
 * @package Daemon\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class CoroutinePoolDaemonCommand
{

    /**
     * 退出
     * @var bool
     */
    public $quit = false;

    /**
     * @var Redis
     */
    public $redis;

    /**
     * CoroutinePoolDaemonCommand constructor.
     */
    public function __construct()
    {
        $this->redis = context()->get('redis');
    }

    /**
     * 主函数
     * @throws \PhpDocReader\AnnotationException
     * @throws \ReflectionException
     */
    public function main()
    {
        // 捕获信号
        ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], function ($signal) {
            $this->quit = true;
            ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], null);
        });
        // 协程池执行任务
        $maxWorkers = 20;
        $maxQueue   = 20;
        $jobQueue   = new Channel($maxQueue);
        $dispatch   = new Dispatcher($jobQueue, $maxWorkers);
        $dispatch->start(CoroutinePoolDaemonWorker::class);
        // 投放任务
        while (true) {
            if ($this->quit) {
                $dispatch->stop();
                return;
            }
            try {
                $data = $this->redis->brPop(['test'], 3);
            } catch (\Throwable $ex) {
                println(sprintf('Error: [%d] %s %s', $ex->getCode(), $ex->getMessage(), get_class($ex)));
                $dispatch->stop();
                return;
            }
            if (!$data) {
                continue;
            }
            $data = array_pop($data); // brPop命令最后一个键才是值
            $jobQueue->push($data);
        }
    }

}
