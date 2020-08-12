<?php

namespace App\Tcp\Commands;

use Mix\Monolog\Handler\RotatingFileHandler;
use Mix\Monolog\Logger;
use Mix\Console\CommandLine\Flag;
use Mix\Server\Connection;
use Mix\Server\Exception\ReceiveException;
use Mix\Server\Server;
use App\Tcp\Exceptions\ExecutionException;
use App\Tcp\Helpers\SendHelper;
use Mix\Signal\SignalNotify;
use Swoole\Coroutine\Channel;

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
    public $logger;

    /**
     * @var callable[]
     */
    public $methods = [
        'foo.bar' => [\App\Tcp\Controllers\FooController::class, 'bar'],
    ];

    /**
     * EOF
     */
    const EOF = "\n";

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->logger = context()->get('logger');
        $this->server = context()->get(Server::class);
    }

    /**
     * 主函数
     * @throws \Swoole\Exception
     */
    public function main()
    {
        // 捕获信号
        // 文件操作 fopen 等在以下代码的前面执行会导致信号捕获失败
        // 因此以下代码必须放在最前面，Swoole >= 4.5.3 已经解决该问题
        $notify = new SignalNotify(SIGHUP, SIGINT, SIGTERM);
        xgo(function () use ($notify) {
            $signal = $notify->channel()->pop();
            $this->logger->info('Received signal [{signal}]', ['signal' => $signal]);
            $this->logger->info('Server shutdown');
            $this->server->shutdown();
            $notify->stop();
        });

        // 设置日志处理器
        $this->logger->withName('TCP');
        $handler = new RotatingFileHandler(sprintf('%s/runtime/logs/tcp.log', app()->basePath), 7);
        $this->logger->pushHandler($handler);

        // 实例化控制器
        foreach ($this->methods as $method => $callback) {
            list($class, $action) = $callback;
            $this->methods[$method] = [new $class, $action];
        }

        // 参数重写
        $host = Flag::string(['h', 'host'], '');
        if ($host) {
            $this->server->host = $host;
        }
        $port = Flag::int(['p', 'port'], 0);
        if ($port) {
            $this->server->port = $port;
        }
        $reusePort = Flag::bool(['r', 'reuse-port'], false);
        if ($reusePort) {
            $this->server->reusePort = $reusePort;
        }

        // 启动服务器
        $this->welcome();
        $server = $this->server;
        $server->handle([$this, 'handle']);
        $server->set([
            'open_eof_check' => true,
            'package_eof'    => static::EOF,
        ]);
        $this->logger->info('Server start');
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
        $sendChan = new Channel();
        xdefer(function () use ($sendChan) {
            $sendChan->close();
        });
        xgo(function () use ($sendChan, $conn) {
            while (true) {
                $data = $sendChan->pop();
                if (!$data) {
                    return;
                }
                try {
                    $conn->send($data);
                } catch (\Throwable $ex) {
                    $conn->close();
                    throw $ex;
                }
            }
        });
        // 消息读取
        while (true) {
            try {
                $data = $conn->recv();
                $this->call($sendChan, $data);
            } catch (\Throwable $ex) {
                // 忽略服务器主动断开连接异常
                if ($ex instanceof ReceiveException && in_array($ex->getCode(), [54, 104])) { // mac=54, linux=104
                    return;
                }
                // 抛出异常
                throw $ex;
            }
        }
    }

    /**
     * 执行功能
     * @param Channel $sendChan
     * @param string $data
     */
    public function call(Channel $sendChan, string $data)
    {
        // 解析数据
        $data = json_decode($data, true);
        if (!$data) {
            SendHelper::error($sendChan, -32600, 'Invalid Request');
            return;
        }
        if (!isset($data['method']) || !isset($data['params']) || !isset($data['id'])) {
            SendHelper::error($sendChan, -32700, 'Parse error', $data['id'] ?? null);
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
        println('Server         Name:      mix-tcp');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$host}");
        println("Listen         Port:      {$port}");
    }

}
