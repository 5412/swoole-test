<?php

$server = new \Swoole\Server('127.0.0.1', 10000);

$process = new Swoole\Process(function($process) use ($server) {
    while (true) {
        $msg = $process->read();
        foreach($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    }
});

$server->addProcess($process);

$server->on('receive', function ($serv, $fd, $reactor_id, $data) use ($process) {
    //群发收到的消息
    $process->write($data);
});

$server->start();