<?php

$socket = new Swoole\Coroutine\Socket(AF_INET, SOCK_STREAM, 0);
$socket->bind('127.0.0.1', 9001);
$socket->listen(128);

go(function () use($socket) {
    while (true) {
        echo "Accept: \n";
        $client = $socket->accept();
        if ($client === false) {
            var_dump($socket->errCode);
        } else {
            var_dump($socket);
            $str = $client->recv();
            var_dump($str);
            $res = $client->send('12312');
            var_dump($res);
        }
    }
});