<?php

namespace App\Console\Commands;

use Mix\Time\Time;

/**
 * Class TimeCommand
 * @package App\Console\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class TimeCommand
{

    /**
     * 主函数
     */
    public function main()
    {
        // 一次性定时
        $timer = Time::newTimer(1 * Time::SECOND);
        xgo(function () use ($timer) {
            $ts = $timer->channel()->pop();
            println($ts);
        });

        // 持续定时
        $ticker = Time::newTicker(1 * Time::SECOND);
        xgo(function () use ($ticker) {
            $count = 0;
            while (true) {
                $ts = $ticker->channel()->pop();
                if (!$ts || $count == 10) {
                    $ticker->stop();
                    return;
                }
                println($ts);
                $count++;
            }
        });
    }

}
