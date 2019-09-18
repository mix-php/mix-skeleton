<?php

namespace App\Tcp\Commands;

use Mix\Concurrent\Coroutine\Channel;
use Mix\Console\CommandLine\Flag;
use Mix\Helper\ProcessHelper;
use Mix\Log\Logger;
use Mix\Server\Connection;
use Mix\Server\Exception\ReceiveFailureException;
use Mix\Server\Server;
use App\Tcp\Exceptions\ExecutionException;
use App\Tcp\Helpers\SendHelper;

/**
 * Class StartCommand
 * @package App\Tcp\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class StartCommand
{

    /**
     * @var Server
     */
    public $server;

    /**
     * @var Logger
     */
    public $log;

    /**
     * @var callable[]
     */
    public $methods = [
        'hello.world' => [\App\Tcp\Controllers\HelloController::class, 'world'],
    ];

    /**
     * EOF
     */
    const EOF = "\r\n";

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->log    = context()->get('log');
        $this->server = context()->get('tcpServer');
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
        $server = $this->server;
        $server->handle(function (Connection $conn) {
            $this->handle($conn);
        });
        $server->set([
            'open_eof_check' => true,
            'package_eof'    => static::EOF,
        ]);
        $this->welcome();
        $this->log->info('server start');
        $server->start();
    }

    /**
     * 连接处理
     * @param Connection $conn
     * @throws \Throwable
     */
    public function handle(Connection $conn)
    {
        // 消息发送
        $sendChan = new Channel(5);
        xdefer(function () use ($sendChan) {
            $sendChan->close();
        });
        xgo(function () use ($sendChan, $conn) {
            while (true) {
                $data = $sendChan->pop();
                if (!$data) {
                    return;
                }
                $conn->send($data);
            }
        });
        // 消息读取
        while (true) {
            try {
                $data = $conn->recv();
            } catch (\Throwable $e) {
                // 忽略服务器主动断开连接异常
                if ($e instanceof ReceiveFailureException && $e->getCode() == 104) {
                    return;
                }
                // 抛出异常
                throw $e;
            }
            $this->runAction($sendChan, $data);
        }
    }

    /**
     * 执行功能
     * @param Channel $sendChan
     * @param $data
     */
    public function runAction(Channel $sendChan, $data)
    {
        // 解析数据
        $data = json_decode($data, true);
        if (!$data) {
            SendHelper::error($sendChan, -32600, 'Invalid Request');
            return;
        }
        if (!isset($data['method']) || !isset($data['params']) || !isset($data['id'])) {
            SendHelper::error($sendChan, -32700, 'Parse error');
            return;
        }
        // 定义变量
        $method = $data['method'];
        $params = $data['params'];
        $id     = $data['id'];
        // 路由到控制器
        if (!isset($this->methods[$method])) {
            SendHelper::error($sendChan, -32601, 'Method not found', $id);
            return;
        }
        // 执行
        try {
            $result = call_user_func($this->methods[$method], $params);
        } catch (ExecutionException $exception) {
            SendHelper::error($sendChan, $exception->getCode(), $exception->getMessage(), $id);
            return;
        }
        SendHelper::result($sendChan, $result, $id);
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
        println('Server         Name:      mix-tcpd');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$host}");
        println("Listen         Port:      {$port}");
    }

}