<?php

namespace App\Console\Commands;

use Mix\Database\Database;
use Swoole\Coroutine\Channel;

/**
 * Class CoroutineCommand
 * @package App\Console\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class CoroutineCommand
{

    /**
     * 主函数
     */
    public function main()
    {
        $time = time();
        $chan = new Channel();
        for ($i = 0; $i < 2; $i++) {
            xgo([$this, 'foo'], $chan);
        }
        for ($i = 0; $i < 2; $i++) {
            $result = $chan->pop();
        }
        println(sprintf('Time: %ds', (time() - $time)));
    }

    /**
     * 查询数据
     * @param Channel $chan
     */
    public function foo(Channel $chan)
    {
        try {
            /** @var Database $db */
            $db     = context()->get('database');
            $result = $db->prepare('select sleep(5)')->queryAll();
            $chan->push($result);
        } catch (\Throwable $exception) {
            $chan->push($exception);
        }
    }

}
