<?php

namespace App\Console\Workers;

use Mix\Concurrent\CoroutinePool\AbstractWorker;
use Swoole\Coroutine\Channel;

/**
 * Class CoroutinePoolDaemonWorker
 * @package Daemon\Libraries
 * @author liu,jian <coder.keda@gmail.com>
 */
class CoroutinePoolDaemonWorker extends AbstractWorker
{

    /**
     * CoroutinePoolDaemonWorker constructor.
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
