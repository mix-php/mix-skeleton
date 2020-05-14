<?php

namespace App\WebSocket\Commands;

use Mix\Console\CommandLine\Flag;
use Mix\Helper\ProcessHelper;
use Mix\Http\Message\Factory\StreamFactory;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Server\Server;
use Mix\Monolog\Logger;
use Mix\Monolog\Handler\RotatingFileHandler;
use Mix\Route\Router;
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
    public $log;

    /**
     * @var Router
     */
    public $route;

    /**
     * @var Upgrader
     */
    public $upgrader;

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->log      = context()->get('log');
        $this->route    = context()->get('webSocketRoute');
        $this->server   = context()->get(Server::class);
        $this->upgrader = context()->get(Upgrader::class);
        // 设置日志处理器
        $this->log->withName('WEBSOCKET');
        $handler = new RotatingFileHandler(sprintf('%s/runtime/logs/websocket.log', app()->basePath), 7);
        $this->log->pushHandler($handler);
    }

    /**
     * 主函数
     * @throws \Swoole\Exception
     */
    public function main()
    {
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
        // 捕获信号
        ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], function ($signal) {
            $this->log->info('received signal [{signal}]', ['signal' => $signal]);
            $this->log->info('server shutdown');
            $this->server->shutdown();
            $this->upgrader->destroy();
            ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], null);
        });
        // 启动服务器
        $this->start();
    }

    /**
     * 启动服务器
     * @throws \Swoole\Exception
     */
    public function start()
    {
        $server = $this->server;
        $server->set([
            //...
        ]);
        $this->welcome();
        $this->log->info('server start');
        $server->start($this->route);
    }

    /**
     * 请求处理
     * @param ServerRequest $request
     * @param Response $response
     */
    public function handle(ServerRequest $request, Response $response)
    {
        try {
            $conn = $this->upgrader->Upgrade($request, $response);
        } catch (\Throwable $e) {
            $response
                ->withBody((new StreamFactory())->createStream('401 Unauthorized'))
                ->withStatus(401)
                ->end();
            return;
        }
        $pathinfo = $request->getServerParams()['path_info'] ?: '/';
        $class    = $this->patterns[$pathinfo];
        $callback = new $class($conn);
        call_user_func($callback);
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
