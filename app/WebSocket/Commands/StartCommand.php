<?php

namespace App\WebSocket\Commands;

use App\Web\Route\Router;
use Mix\Console\CommandLine\Flag;
use Mix\Http\Server\Server;
use Mix\Monolog\Logger;
use Mix\Monolog\Handler\RotatingFileHandler;
use Mix\Signal\SignalNotify;
use Mix\WebSocket\Upgrader;

/**
 * Class StartCommand
 * @package App\WebSocket\Commands
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
     * @var Router
     */
    public $router;

    /**
     * @var Upgrader
     */
    public $upgrader;

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->logger   = context()->get('logger');
        $this->router   = context()->get('webSocketRouter');
        $this->server   = context()->get(Server::class);
        $this->upgrader = context()->get(Upgrader::class);
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
            $this->upgrader->destroy();
            $notify->stop();
        });

        // 设置日志处理器
        $this->logger->withName('WEBSOCKET');
        $handler = new RotatingFileHandler(sprintf('%s/runtime/logs/websocket.log', app()->basePath), 7);
        $this->logger->pushHandler($handler);

        // 参数重写
        $host = Flag::string(['h', 'host'], '');
        if ($host) {
            $this->server->host = $host;
        }
        $port = Flag::int(['p', 'port'], 9512);
        if ($port) {
            $this->server->port = $port;
        }
        $reusePort = Flag::bool(['r', 'reuse-port'], false);
        if ($reusePort) {
            $this->server->reusePort = $reusePort;
        }

        // 启动服务器
        $this->welcome();
        $this->logger->info('Server start');
        $this->server->start($this->router);
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
        println('Server         Name:      mix-websocket');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$host}");
        println("Listen         Port:      {$port}");
    }

}
