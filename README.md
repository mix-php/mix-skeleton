## Mix Skeleton

本项目是 MixPHP 全功能开发程序骨架，包含：

> 每个模块中都包含范例代码

- `Console` 开发命令行程序
- `Http` 开发 HTTP 接口、网站
- `Tcp` 开发各种 RPC 服务，基于 mqtt 协议的物联网项目等
- `Udp` 开发基于 UDP 的日志接收系统等
- `WebSocket` 开发消息推送、在线聊天、直播弹幕、棋牌游戏等

## 开发文档

MixPHP 开发指南：http://doc.mixphp.cn

## 环境要求

* Linux, OS X, WSL
* PHP >= 7.2
* Swoole >= 4.4.4 (websocket >= 4.4.8)

## 快速开始

推荐使用 [composer](https://www.phpcomposer.com/) 安装。

安装最新版本：

```shell
composer create-project --prefer-dist mix/mix-skeleton mix 2.1.*
```

## License

Apache License Version 2.0, http://www.apache.org/licenses/
