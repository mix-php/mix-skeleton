<?php

namespace App\WebSocket\Handlers;

use Swoole\Coroutine\Channel;
use Mix\WebSocket\Connection;
use Mix\WebSocket\Exception\CloseFrameException;
use Mix\WebSocket\Exception\ReceiveException;
use App\WebSocket\Controllers\JoinController;
use App\WebSocket\Controllers\MessageController;
use App\WebSocket\Exceptions\ExecutionException;
use App\WebSocket\Helpers\SendHelper;
use App\WebSocket\Session\Session;

/**
 * Class WebSocketHandler
 * @package App\WebSocket\Handlers
 * @author liu,jian <coder.keda@gmail.com>
 */
class WebSocketHandler
{

    /**
     * @var Connection
     */
    public $conn;

    /**
     * @var Channel
     */
    public $sendChan;

    /**
     * @var Session
     */
    public $session;

    /**
     * @var callable[]
     */
    public $methods = [
        'join.room'    => [JoinController::class, 'room'],
        'message.emit' => [MessageController::class, 'emit'],
    ];

    /**
     * WebSocketHandler constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $conn)
    {
        $this->conn     = $conn;
        $this->sendChan = new Channel();
        $this->session  = new Session($this->sendChan, $this->conn);
        $this->init();
    }

    /**
     * 初始化
     */
    public function init()
    {
        // 实例化控制器
        foreach ($this->methods as $method => $callback) {
            list($class, $action) = $callback;
            $this->methods[$method] = [new $class, $action];
        }
    }

    /**
     * Invoke
     */
    function __invoke()
    {
        // 消息发送
        xgo(function () {
            while (true) {
                $frame = $this->sendChan->pop();
                if (!$frame) {
                    return;
                }
                try {
                    $this->conn->send($frame);
                } catch (\Throwable $e) {
                    $this->conn->close();
                    throw $e;
                }
            }
        });
        // 消息读取
        while (true) {
            try {
                $frame = $this->conn->recv();
                $this->runAction($frame->data);
            } catch (\Throwable $e) {
                // 清理会话资源
                $this->session->clear();
                // 忽略服务器主动断开连接异常
                if ($e instanceof ReceiveException && in_array($e->getCode(), [54, 104])) { // mac=54, linux=104
                    return;
                }
                // 忽略客户端主动断开连接异常
                if ($e instanceof CloseFrameException && in_array($e->getCode(), [1000, 1001])) {
                    return;
                }
                // 抛出异常
                throw $e;
            }
        }
    }

    /**
     * 执行功能
     * @param $data
     */
    public function runAction($data)
    {
        // 解析数据
        $data = json_decode($data);
        if (!$data) {
            SendHelper::error($this->sendChan, -32600, 'Invalid Request');
            return;
        }
        if (!isset($data->method) || (!isset($data->params) or !is_array($data->params)) || !isset($data->id)) {
            SendHelper::error($this->sendChan, -32700, 'Parse error');
            return;
        }
        // 定义变量
        $method = $data->method;
        $params = $data->params;
        $id     = $data->id;
        // 路由到控制器
        if (!isset($this->methods[$method])) {
            SendHelper::error($this->sendChan, -32601, 'Method not found', $id);
            return;
        }
        // 执行
        try {
            $result = call_user_func($this->methods[$method], $this->session, $params);
        } catch (ExecutionException $exception) {
            SendHelper::error($this->sendChan, $exception->getCode(), $exception->getMessage(), $id);
            return;
        }
        SendHelper::result($this->sendChan, $result, $id);
    }

}
