<?php

namespace App\Console\Workers;

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
     */
    public function __construct(Channel $workerPool)
    {
        parent::__construct($workerPool);
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
