<?php

use app\common\model\MyWebSocketServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;

require __DIR__ . '/vendor/autoload.php';

// 创建事件循环
$loop = Loop::get();

$webSocketController = new MyWebSocketServer($loop);

$webServer = new IoServer(
    new HttpServer(
        new WsServer(
            $webSocketController
        )
    ),
    new React\Socket\Server('127.0.0.1:8080', $loop), // 使用 localhost
    $loop
);

echo "WebSocket server started at ws://127.0.0.1:8080\n";

$loop->run();
