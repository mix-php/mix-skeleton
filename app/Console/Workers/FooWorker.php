<?php

namespace App\Console\Workers;

use Mix\Sync\WaitGroup;
use Mix\WorkerPool\AbstractWorker;
use Swoole\Coroutine\Channel;

/**
 * Class FooWorker
 * @package App\Console\Workers
 */
class FooWorker extends AbstractWorker
{

    /**
     * FooWorker constructor.
     * @param Channel $workerPool
     * @param WaitGroup $waitGroup
     */
    public function __construct(Channel $workerPool, WaitGroup $waitGroup)
    {
        parent::__construct($workerPool, $waitGroup);
        // 实例化一些需重用的对象
        // ...
    }

    /**
     * 处理
     * @param $data
     */
    public function handle($data)
    {
        var_dump($data);
    }

}
