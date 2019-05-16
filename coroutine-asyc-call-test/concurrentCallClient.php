<?php

echo 'script begin', PHP_EOL;

echo 'asyc call mysql', PHP_EOL;

go(function () {
    echo 'no setDefer' , PHP_EOL;
    $mysql_config = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'example',
        'database' => 'test',
    ];
    $beginTime = microtime(1);
    $mysql = new Swoole\Coroutine\MySQL();
    if ($mysql->connect($mysql_config)) {
        $res = $mysql->query("select * from market_client");
        //var_export($res);
    } else {
        echo $mysql->error, PHP_EOL;
    }
    $mysql->close();

    $redis = new Swoole\Coroutine\Redis();
    if ($redis->connect('127.0.0.1', 6379)) {
        //$redis->set('clientList', json_encode($res));
        $list = $redis->get('clientList');
        //var_dump($list);
    }
    $redis->close();

    $httpClient = new \Swoole\Coroutine\Http\Client('https://www.baidu.com', 80);
    $httpClient->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $httpClient->set([ 'timeout' => 1]);
    $httpClient->get('/index.php');
    //echo $cli->body;
    $httpClient->close();

    $endTime = microtime(1);
    echo 'no defer timing: ', $endTime - $beginTime, PHP_EOL;
});

go(function () {
    echo 'setDefer' , PHP_EOL;
    $mysql_config = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'example',
        'database' => 'test',
    ];
    $beginTime = microtime(1);
    $mysql = new Swoole\Coroutine\MySQL();
    if ($mysql->connect($mysql_config)) {
        $mysql->setDefer();
        $mysql->query("select * from market_client");
        $res = $mysql->recv();
        //var_export($res);
    } else {
        echo $mysql->error, PHP_EOL;
    }
    $mysql->close();

    $redis = new Swoole\Coroutine\Redis();
    if ($redis->connect('127.0.0.1', 6379)) {
        //$redis->set('clientList', json_encode($res));
        $redis->setDefer();
        $redis->get('clientList');
        $list = $redis->recv();
        //var_dump($list);
    }
    $redis->close();

    $httpClient = new \Swoole\Coroutine\Http\Client('https://www.baidu.com', 80);
    $httpClient->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $httpClient->set([ 'timeout' => 1]);
    $httpClient->setDefer();
    $httpClient->get('/index.php');
    //echo $cli->body;
    $httpClient->recv();
    $httpClient->close();
    $endTime = microtime(1);
    echo 'timing: ', $endTime - $beginTime, PHP_EOL;
});