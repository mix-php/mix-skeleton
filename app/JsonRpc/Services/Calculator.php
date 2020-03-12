<?php

namespace App\JsonRpc\Services;

/**
 * Class Calculator
 * @package App\JsonRpc\Services
 * @author liu,jian <coder.keda@gmail.com>
 */
class Calculator
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
