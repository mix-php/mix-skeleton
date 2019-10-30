<?php

namespace App\WebSocket\Session;

use Mix\Redis\Subscribe\Subscriber;
use Mix\WebSocket\Connection;
use Swoole\Coroutine\Channel;

/**
 * Class Session
 * @package App\WebSocket\Session
 * @author liu,jian <coder.keda@gmail.com>
 */
class Session
{

    /**
     * @var Channel
     */
    public $sendChan;

    /**
     * @var Connection
     */
    public $conn;

    /**
     * @var string
     */
    public $joinRoomId;

    /**
     * @var string
     */
    public $joinName;

    /**
     * @var Subscriber
     */
    public $subscriber;

    /**
     * Session constructor.
     * @param Channel $sendChan
     * @param Connection $conn
     */
    public function __construct(Channel $sendChan, Connection $conn)
    {
        $this->sendChan = $sendChan;
        $this->conn     = $conn;
    }

    /**
     * 清除
     */
    public function clear()
    {
        $this->sendChan->close();
        $this->conn->close();
        if (isset($this->subscriber)) {
            $this->subscriber->close();
        }
    }

}
