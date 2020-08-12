<?php

namespace App\Udp\Commands;

use App\Udp\Exceptions\ExecutionException;
use App\Udp\Helpers\SendHelper;
use App\Udp\Server\Server;
use Mix\Monolog\Handler\RotatingFileHandler;
use Mix\Monolog\Logger;
use Mix\Console\CommandLine\Flag;
use Mix\Signal\SignalNotify;
use Swoole\Coroutine\Channel;

/**
 * Class StartCommand
 * @package App\Udp\Commands
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
     * @var Channel
     */
    public $sendChan;

    /**
     * @var callable[]
     */
    public $methods = [
        'foo.bar' => [\App\Udp\Controllers\FooController::class, 'bar'],
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
        $this->logger   = context()->get('logger');
        $this->server   = context()->get(Server::class);
        $this->sendChan = new Channel();
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
        $this->logger->withName('UDP');
        $handler = new RotatingFileHandler(sprintf('%s/runtime/logs/udp.log', app()->basePath), 7);
        $this->logger->pushHandler($handler);

        // 实例化控制器
        foreach ($this->methods as $method => $callback) {
            list($class, $action) = $callback;
            $this->methods[$method] = [new $class, $action];
        }

        // 消息发送
        xgo(function () {
            while (true) {
                $result = $this->sendChan->pop();
                if (!$result) {
                    return;
                }
                try {
                    list($data, $peer) = $result;
                    $this->server->send($data . static::EOF, $peer['port'], $peer['address']);
                } catch (\Throwable $ex) {
                    $this->server->shutdown();
                    throw $ex;
                }
            }
        });

        // 参数重写
        $addr = Flag::string(['a', 'addr'], '');
        if ($addr) {
            $this->server->address = $addr;
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
        $this->logger->info('Server start');
        $server->start();
    }

    /**
     * 消息处理
     * @param string $data
     * @param array $peer
     */
    public function handle(string $data, array $peer)
    {
        $sendChan = $this->sendChan;
        // 解析数据
        $data = json_decode($data, true);
        if (!$data) {
            SendHelper::error($sendChan, $peer, -32600, 'Invalid Request');
            return;
        }
        if (!isset($data['method']) || !isset($data['params']) || !isset($data['id'])) {
            SendHelper::error($sendChan, $peer, -32700, 'Parse error', $data['id'] ?? null);
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
        $addr          = $this->server->address;
        $port          = $this->server->port;
        echo <<<EOL
                              ____
 ______ ___ _____ ___   _____  / /_ _____
  / __ `__ \/ /\ \/ /__ / __ \/ __ \/ __ \
 / / / / / / / /\ \/ _ / /_/ / / / / /_/ /
/_/ /_/ /_/_/ /_/\_\  / .___/_/ /_/ .___/
                     /_/         /_/


EOL;
        println('Server         Name:      mix-udp');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$addr}");
        println("Listen         Port:      {$port}");
    }

}
