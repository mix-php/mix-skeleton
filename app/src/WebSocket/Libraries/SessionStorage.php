<?php

namespace App\WebSocket\Libraries;

use App\WebSocket\Controllers\JoinController;

/**
 * Class SessionStorage
 * @package App\WebSocket\Libraries
 * @author liu,jian <coder.keda@gmail.com>
 */
class SessionStorage
{

    /**
     * @var JoinController
     */
    public $joinController;

    /**
     * 清除
     */
    public function clear()
    {
        if ($this->joinController) {
            $this->joinController->subChan->close();
            $this->joinController->subStopChan->close();
            $this->joinController->subWaitChan->close();
        }
    }

}
