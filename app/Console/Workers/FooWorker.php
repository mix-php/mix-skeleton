<?php

namespace App\Console\Workers;

use Mix\WorkerPool\AbstractWorker;

/**
 * Class FooWorker
 * @package App\Console\Workers
 */
class FooWorker extends AbstractWorker
{

    /**
     * FooWorker constructor.
     */
    public function __construct()
    {
        // 实例化一些需重用的对象
        // ...
    }

    /**
     * 处理
     * @param $data
     */
    public function do($data)
    {
        var_dump($data);
    }

}
