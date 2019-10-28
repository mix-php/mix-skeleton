<?php

namespace App\WebSocket\Controllers;

use Mix\Redis\Pool\ConnectionPool;
use Mix\Redis\Subscribe\Subscriber;
use App\WebSocket\Exceptions\ExecutionException;
use App\WebSocket\Helpers\JsonRpcHelper;
use App\WebSocket\Forms\JoinForm;
use App\WebSocket\Libraries\SessionStorage;

/**
 * Class JoinController
 * @package App\WebSocket\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JoinController
{

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
     * JoinController constructor.
     */
    public function __construct()
    {
        $this->subscriber = context()->get(Subscriber::class);
    }

    /**
     * 加入房间
     * @param SessionStorage $sessionStorage
     * @param $params
     * @return array
     */
    public function room(SessionStorage $sessionStorage, $params)
    {
        // 验证数据
        $attributes = [
            'roomId' => array_shift($params),
            'name'   => array_shift($params),
        ];
        $model      = new JoinForm($attributes);
        $model->setScenario('room');
        if (!$model->validate()) {
            throw new ExecutionException($model->getError(), 100001);
        }

        // 订阅新的频道，取消之前的订阅
        try {
            if ($this->joinRoomId) {
                $this->subscriber->unsubscribe("room_{$this->joinRoomId}");
            }
            $this->subscriber->subscribe("room_{$model->roomId}");
            $this->joinRoomId = $model->roomId;
            $this->joinName   = $model->name;
        } catch (\Throwable $e) {
            // 订阅失败
            throw new ExecutionException($e->getMessage(), 100002);
        }

        // 给其他订阅当前房间的连接发送加入消息
        /** @var ConnectionPool $redisPool */
        $redisPool = context()->get(ConnectionPool::class);
        $redis     = $redisPool->getConnection();
        $message   = JsonRpcHelper::notification('message.update', [
            sprintf('%s joined the room', $model->name),
            $model->roomId,
        ]);
        $redis->publish("room_{$model->roomId}", $message);

        // 给当前连接发送加入消息
        return [
            'status' => 'success',
        ];
    }

}
