<?php

$server = new \Swoole\Server('127.0.0.1', 10000);

$server->on('connect', function ($server, $fd) {
    echo 'someone connect us' , $fd, PHP_EOL;
});

$server->on('receive', function($server, $fd, $reactor_id, $data) {
    $server->tick(1000, function () use ($server, $fd) {
       $server->send($fd, 'hello again');
    });
});

$server->on('close', function ($server, $fd) {
    echo 'someone close', $fd, PHP_EOL;
});
$server->start();