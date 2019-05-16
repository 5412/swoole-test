<?php

echo 'script begin', PHP_EOL;
go(function () {
    echo 'setDefer' , PHP_EOL;
    $beginTime = microtime(1);
    //并发请求 n
    $n = 1000;
    for ($i = 0; $i < $n; $i++) {
        $cli = new Swoole\Coroutine\Http\Client('127.0.0.1', 8081);
        $cli->setHeaders([
            'Host' => "local.ad.oa.com",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip',
        ]);
        $cli->set([ 'timeout' => 2]);
        $cli->setDefer();
        $cli->get('/');
        $clients[] = $cli;
    }

    for ($i = 0; $i < $n; $i++) {
        $r = $clients [$i]->recv();
        $clients [$i]->close();
        $result[] = $clients[$i]->body;
    }
    //$str = var_export($result);

    $endTime = microtime(1);
    echo 'timing: ', $endTime - $beginTime, PHP_EOL;
});