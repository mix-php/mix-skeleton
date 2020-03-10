<?php

namespace App\JsonRpc\Commands;

use Mix\Console\CommandLine\Flag;
use Mix\Helper\ProcessHelper;
use Mix\Log\Logger;
use Mix\JsonRpc\Server;
use Mix\Http\Server\Server as HttpServer;

/**
 * Class StartCommand
 * @package App\Sync\Commands
 * @author liu,jian <coder.keda@gmail.com>
 */
class StartCommand
{

    /**
     * @var Server
     */
    public $rpcServer;

    /**
     * @var HttpServer
     */
    public $httpServer;

    /**
     * @var Logger
     */
    public $log;

    /**
     * @var string[]
     */
    public $services = [
        \App\JsonRpc\Services\Foo::class,
    ];

    /**
     * StartCommand constructor.
     */
    public function __construct()
    {
        $this->log        = context()->get('log');
        $this->rpcServer  = context()->get(Server::class);
        $this->httpServer = context()->get(HttpServer::class);
    }

    /**
     * 主函数
     */
    public function main()
    {
        // 参数重写
        $host = Flag::string(['h', 'host'], '');
        if ($host) {
            $this->rpcServer->host  = $host;
            $this->httpServer->host = $host;
        }
        $tcpPort = Flag::string(['p', 'tcp-port'], '');
        if ($tcpPort) {
            $this->rpcServer->port = $tcpPort;
        }
        $httpPort = Flag::string(['P', 'http-port'], '');
        if ($httpPort) {
            $this->httpServer->port = $httpPort;
        }
        $reusePort = Flag::bool(['r', 'reuse-port'], false);
        if ($reusePort) {
            $this->rpcServer->reusePort  = $reusePort;
            $this->httpServer->reusePort = $reusePort;
        }
        // 捕获信号
        ProcessHelper::signal([SIGINT, SIGTERM, SIGQUIT], function ($signal) {
            $this->log->info('received signal [{signal}]', ['signal' => $signal]);
            $this->log->info('server shutdown');
            $this->httpServer->shutdown();
            $this->rpcServer->shutdown();
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
        $this->welcome();
        $this->log->info('server start');
        foreach ($this->services as $service) {
            $this->rpcServer->register(new $service);
        }
        xgo(function () {
            $this->rpcServer->start();
        });
        $this->httpServer->start($this->rpcServer);
    }

    /**
     * 欢迎信息
     */
    protected function welcome()
    {
        $phpVersion    = PHP_VERSION;
        $swooleVersion = swoole_version();
        $host          = $this->rpcServer->host;
        $tcpPort       = $this->rpcServer->port;
        $httpPort      = $this->httpServer->port;
        echo <<<EOL
                              ____
 ______ ___ _____ ___   _____  / /_ _____
  / __ `__ \/ /\ \/ /__ / __ \/ __ \/ __ \
 / / / / / / / /\ \/ _ / /_/ / / / / /_/ /
/_/ /_/ /_/_/ /_/\_\  / .___/_/ /_/ .___/
                     /_/         /_/


EOL;
        println('Server         Name:      mix-jsonrpcd');
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Mix::$version);
        println("Listen         Addr:      {$host}");
        println("TCP            Port:      {$tcpPort}");
        println("HTTP           Port:      {$httpPort}");
    }

}
