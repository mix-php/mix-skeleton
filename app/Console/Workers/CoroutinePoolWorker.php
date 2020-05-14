<?php

namespace App\Console\Workers;

use Mix\Concurrent\CoroutinePool\AbstractWorker;
use Swoole\Coroutine\Channel;

/**
 * Class CoroutinePoolWorker
 * @package App\Console\Libraries
 * @author liu,jian <coder.keda@gmail.com>
 */
class CoroutinePoolWorker extends AbstractWorker
{

    /**
     * CoroutinePoolWorker constructor.
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
