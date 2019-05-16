<?php
$server = new swoole_websocket_server("127.0.0.1", 9502);
$server->set([
    'enable_static_handler' => true,
    'document_root' => __DIR__ . '/web',
]);

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$tcp = $server->listen("0.0.0.0", 9515, SWOOLE_SOCK_TCP);
$tcp->set([
    'open_length_check' => true,
    'package_max_length' => 2 * 1024 * 1024,
    'package_length_type' => 'N',
    'package_body_offset' => 16,
    'package_length_offset' => 0,
]);

$server->on("open", function ($serv, $req) {
    echo "new WebSocket Client, fd={$req->fd}\n";
});

$tcp->on('receive', function ($server, $fd, $reactor_id, $data) {
    echo 'here', PHP_EOL;
    $body = substr($data, 0);
    $value = swoole_serialize::unpack($body);
    //仅遍历 9514 端口的连接
    $websocket = $server->ports[0];
    foreach ($websocket->connections as $_fd)
    {
        if ($server->exist($_fd))
        {
            $server->push($_fd, json_encode($value));
        }
    }
});

$server->start();

$server->start();