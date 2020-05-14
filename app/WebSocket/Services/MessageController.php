<?php

namespace App\WebSocket\Services;

use App\WebSocket\Exceptions\ExecutionException;
use App\WebSocket\Forms\MessageForm;
use App\WebSocket\Helpers\JsonRpcHelper;
use App\WebSocket\Session\Session;
use Mix\Redis\Redis;

/**
 * Class MessageController
 * @package App\WebSocket\Services
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
        /** @var Redis $redis */
        $redis   = context()->get('redis');
        $message = JsonRpcHelper::notification('message.update', [
            $model->text,
            $session->joinRoomId,
            $session->joinName,
        ]);
        $redis->publish("room_{$session->joinRoomId}", $message);

        // 给当前连接发送消息
        return [
            'status' => 'success',
        ];
    }

}
