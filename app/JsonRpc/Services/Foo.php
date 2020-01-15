<?php

namespace App\JsonRpc\Services;

/**
 * Class Foo
 * @package App\JsonRpc\Services
 * @author liu,jian <coder.keda@gmail.com>
 */
class Foo
{

    /**
     * Sum
     * @param int $a
     * @param int $b
     * @return int
     */
    public function Sum(int $a, int $b): int
    {
        return $a + $b;
    }

}
