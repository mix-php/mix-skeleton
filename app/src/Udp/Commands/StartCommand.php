<?php

namespace App\Udp\Commands;

use App\Udp\Exceptions\ExecutionException;
use App\Udp\Helpers\SendHelper;
use Mix\Concurrent\Coroutine\Channel;
use Mix\Console\CommandLine\Flag;
use Mix\Helper\ProcessHelper;
use Mix\Log\Logger;
use Mix\Udp\Server\UdpServer;

/**
 * Class StartCommand
 * @package App\Udp\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class StartCommand
{

    /**
     * @var UdpServer
     */
    public $server;

    /**
     * @var Logger
     */
    public $log;

    /**
     * @var Channel
     */
    public $sendChan;

    /**
     * @var callable[]
     */
    public $methods = [
        'hello.world' => [\App\Udp\Controllers\HelloController::class, 'world'],
    ];

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->log      = context()->get('log');
        $this->server   = context()->get('udpServer');
        $this->sendChan = new Channel(5);
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
     * 主函数
     */
    public function main()
    {
        // 守护处理
        $daemon = Flag::bool(['d', 'daemon'], false);
        if ($daemon) {
            ProcessHelper::daemon();
        }
        // 参数重写
        $host = Flag::string(['h', 'host'], '');
        if ($host) {
            $this->server->host = $host;
        }
        $port = Flag::string(['p', 'port'], '');
        if ($port) {
            $this->server->port = $port;
        }
        // 捕获信号
        ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], function ($signal) {
            $this->log->info('received signal [{signal}]', ['signal' => $signal]);
            $this->log->info('server shutdown');
            $this->server->shutdown();
            $this->sendChan->close();
            ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], null);
        });
        // 启动服务器
        $this->start();
    }

    /**
     * 启动服务器
     */
    public function start()
    {
        // 消息发送
        xgo(function () {
            while (true) {
                $res = $this->sendChan->pop();
                if (!$res) {
                    return;
                }
                list($data, $peer) = $res;
                $this->server->swooleSocket->sendTo($peer['address'], $peer['port'], $data . "\n");
            }
        });
        // 消息处理
        $server = $this->server;
        $server->handle(function (\Swoole\Coroutine\Socket $socket, string $data, array $peer) {
            $this->handle($this->sendChan, $data, $peer);
        });
        $this->welcome();
        $this->log->info('server start');
        $server->start();
    }

    /**
     * 消息处理
     * @param Channel $sendChan
     * @param string $data
     * @param array $peer
     */
    public function handle(Channel $sendChan, string $data, array $peer)
    {
        // 解析数据
        $data = json_decode($data, true);
        if (!$data) {
            SendHelper::error($sendChan, $peer, -32600, 'Invalid Request');
            return;
        }
        if (!isset($data['method']) || !isset($data['params']) || !isset($data['id'])) {
            SendHelper::error($sendChan, $peer, -32700, 'Parse error');
            return;
        }
        // 定义变量
        $method = $data['method'];
        $params = $data['params'];
        $id     = $data['id'];
        // 路由到控制器
        if (!isset($this->methods[$method])) {
            SendHelper::error($sendChan, $peer, -32601, 'Method not found', $id);
            return;
        }
        // 执行
        try {
            $result = call_user_func($this->methods[$method], $params);
        } catch (ExecutionException $exception) {
            SendHelper::error($sendChan, $exception->getCode(), $exception->getMessage(), $id);
            return;
        }
        SendHelper::result($sendChan, $peer, $result, $id);
    }

    /**
     * 欢迎信息
     */
    protected function welcome()
    {
        $phpVersion    = PHP_VERSION;
        $swooleVersion = swoole_version();
        $host          = $this->server->host;
        $port          = $this->server->port;
        echo <<<EOL
                              ____
 ______ ___ _____ ___   _____  / /_ _____
  / __ `__ \/ /\ \/ /__ / __ \/ __ \/ __ \
 / / / / / / / /\ \/ _ / /_/ / / / / /_/ /
/_/ /_/ /_/_/ /_/\_\  / .___/_/ /_/ .___/
                     /_/         /_/


EOL;
        println('Server         Name:      mix-udpd');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$host}");
        println("Listen         Port:      {$port}");
    }

}
