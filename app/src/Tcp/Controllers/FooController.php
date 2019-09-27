<?php

namespace App\Tcp\Controllers;

/**
 * Class FooController
 * @package App\Tcp\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class FooController
{

    /**
     * Method demo
     * @param $params
     * @return array
     */
    public function bar($params)
    {
        return [
            'Hello, World!',
        ];
    }

}
