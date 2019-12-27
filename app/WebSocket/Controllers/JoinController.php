<?php

namespace App\WebSocket\Controllers;

use Mix\Redis\Pool\ConnectionPool;
use Mix\Redis\Subscribe\Message;
use Mix\Redis\Subscribe\Subscriber;
use App\WebSocket\Exceptions\ExecutionException;
use App\WebSocket\Helpers\JsonRpcHelper;
use App\WebSocket\Forms\JoinForm;
use App\WebSocket\Session\Session;
use Swoole\WebSocket\Frame;

/**
 * Class JoinController
 * @package App\WebSocket\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JoinController
{

    /**
     * 加入房间
     * @param Session $session
     * @param $params
     * @return array
     */
    public function room(Session $session, $params)
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

        // 创建订阅器
        if (!isset($session->subscriber)) {
            $session->subscriber = context()->get(Subscriber::class);
            // 订阅消息处理
            xgo(function () use ($session) {
                $chan = $session->subscriber->channel();
                while (true) {
                    /** @var Message $message */
                    $message = $chan->pop();
                    if (empty($message)) {
                        if (!$session->subscriber->closed) {
                            // redis异常断开处理
                            $session->clear();
                        }
                        return;
                    }
                    $frame         = new Frame();
                    $frame->opcode = SWOOLE_WEBSOCKET_OPCODE_TEXT;
                    $frame->data   = $message->payload;
                    $session->sendChan->push($frame);
                }
            });
        }

        // 订阅新的频道，取消之前的订阅
        try {
            if ($session->joinRoomId) {
                $session->subscriber->unsubscribe("room_{$session->joinRoomId}");
            }
            $session->subscriber->subscribe("room_{$model->roomId}");
            $session->joinRoomId = $model->roomId;
            $session->joinName   = $model->name;
        } catch (\Throwable $e) {
            // 订阅失败
            throw new ExecutionException($e->getMessage(), 100002);
        }

        // 给其他订阅当前房间的连接发送加入消息
        /** @var ConnectionPool $redisPool */
        $redisPool = context()->get('redisPool');
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
