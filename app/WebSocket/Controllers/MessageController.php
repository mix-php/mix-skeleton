<?php

namespace App\WebSocket\Controllers;

use Mix\Redis\Pool\ConnectionPool;
use App\WebSocket\Exceptions\ExecutionException;
use App\WebSocket\Forms\MessageForm;
use App\WebSocket\Helpers\JsonRpcHelper;
use App\WebSocket\Session\Session;

/**
 * Class MessageController
 * @package App\WebSocket\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class MessageController
{

    /**
     * 发送消息
     * @param Session $session
     * @param $params
     * @return array
     */
    public function emit(Session $session, $params)
    {
        // 使用模型
        $attributes = [
            'text' => array_shift($params),
        ];
        $model      = new MessageForm($attributes);
        $model->setScenario('emit');
        // 验证失败
        if (!$model->validate()) {
            throw new ExecutionException($model->getError(), 100001);
        }

        // 获取加入的房间id
        if (empty($session->joinRoomId)) {
            // 给当前连接发送消息
            throw new ExecutionException("You didn't join any room", 100003);
        }

        // 给当前加入的房间发送消息
        $message = JsonRpcHelper::notification('message.update', [
            $model->text,
            $session->joinRoomId,
            $session->joinName,
        ]);
        /** @var ConnectionPool $pool */
        $pool  = context()->get('redisPool');
        $redis = $pool->getConnection();
        $redis->publish("room_{$session->joinRoomId}", $message);
        $redis->release();

        // 给当前连接发送消息
        return [
            'status' => 'success',
        ];
    }

}
