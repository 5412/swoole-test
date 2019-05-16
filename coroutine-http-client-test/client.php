<?php

use Swoole\Coroutine\Http\Client;

go(function () {
    $cli = new Client('127.0.0.1', 8000);
    $cli->set([
        'timeout' => 3.0,
        'keep_alive' => false,
    ]);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->setCookies(['user' => 'solar']);
    //$cli->setMethod('POST');
    $cli->setData('foo=bar&bar=foo'); // 请求自动转为POST rawData
    $cli->addFile('./test.txt', 'file', 'application/text', 'ha.txt', 1,1);
    $cli->addData('dasdas', 'file1', 'application/text', 'hah.txt');
    $cli->execute('/index.php');
    echo $cli->errCode, PHP_EOL;
    echo $cli->statusCode, PHP_EOL;
    echo $cli->body, PHP_EOL;

    $cli->post('/post.php', array("a" => '1234', 'b' => '456'));
    echo $cli->body;
    $cli->close();

    $cli->get('/solar.php');
    echo $cli->errCode, PHP_EOL;
    echo $cli->statusCode, PHP_EOL;
    echo $cli->body, PHP_EOL;
});

go(function () {
    $cli = new Client('127.0.0.1', 8000);
    $cli->set([
        'timeout' => 3.0,
        'keep_alive' => false,
    ]);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->setCookies(['user' => 'solar']);
    //$cli->setMethod('POST');
    $cli->setData('foo=bar&bar=foo'); // 请求自动转为POST rawData
    $cli->addFile('./test.txt', 'file', 'application/text', 'ha.txt', 1,1);
    $cli->addData('dasdas', 'file1', 'application/text', 'hah.txt');
    $cli->execute('/index.php');
    echo $cli->errCode, PHP_EOL;
    echo $cli->statusCode, PHP_EOL;
    echo $cli->body, PHP_EOL;
});

go(function () {
    $cli = new Client('127.0.0.1', 8000);
    $cli->set([
        'timeout' => 3.0,
        'keep_alive' => false,
    ]);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->setCookies(['user' => 'solar']);
    $cli->post('/post.php', array("a" => '1234', 'b' => '456'));
    echo $cli->body;
    $cli->close();
});

go(function () {
    $cli = new Client('127.0.0.1', 8000);
    $cli->set([
        'timeout' => 3.0,
        'keep_alive' => false,
    ]);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->setCookies(['user' => 'solar']);
    $cli->get('/solar.php');
    echo $cli->errCode, PHP_EOL;
    echo $cli->statusCode, PHP_EOL;
    echo $cli->body, PHP_EOL;
});

go(function () {
    $cli = new Swoole\Coroutine\Http\Client('127.0.0.1', 8000);
    $cli->setHeaders([
        'Host' => "localhost",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->set([ 'timeout' => 1]);
    $cli->setDefer();
    $cli->get('/');
    echo $cli->body, PHP_EOL;
    co::sleep(1);
    $data = $cli->recv();
    echo $data, PHP_EOL;
    echo $cli->body, PHP_EOL;
});

go(function () {
    $host = 'www.swoole.com';
    $cli = new \Swoole\Coroutine\Http\Client($host, 443, true);
    $cli->set(['timeout' => -1]);
    $cli->setHeaders([
        'Host' => $host,
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => '*',
        'Accept-Encoding' => 'gzip'
    ]);
    $cli->download('/static/files/swoole-logo.svg', __DIR__ . '/logo.svg');
});

echo 'main script', PHP_EOL;



