<?php

$server = new swoole_http_server('0.0.0.0', 8888);
$server->on('request', 'onRequest');
$server->start();

function onRequest(swoole_http_request $request, swoole_http_response $response) {
    $uri = $request->server['request_uri'];
    switch ($uri) {
        case '/favicon.ico':
            $response->status(404);
            $response->end();
            break;
        default:
            echo $uri, PHP_EOL;
            $func = substr($uri, 1);
            if (function_exists($func)) {
                $func($request, $response);
            } else {
                $response->end('Hello stranger');
                break;
            }
    }
}

function asycChannel(swoole_http_request $request, swoole_http_response $response) {
    $begin = microtime(1);
    $chan = new chan(2);
    go(function () use ($chan) {
        $cli = new Swoole\Coroutine\Http\Client('www.qq.com', 80);
        $cli->set(['timeout' => 10]);
        $cli->setHeaders([
            'Host' => "www.qq.com",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip',
        ]);
        $cli->get('/');
        $chan->push(['www.qq.com' => $cli->body]);
    });

    go(function () use ($chan) {
        $cli = new Swoole\Coroutine\Http\Client('www.163.com', 80);
        $cli->set(['timeout' => 10]);
        $cli->setHeaders([
            'Host' => "www.163.com",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip',
        ]);
        $cli->get('/');
        $chan->push(['www.163.com' => $cli->body]);
    });

    $result = [];
    for ($i = 0; $i < 2; $i++)
    {
        $result += $chan->pop();
    }
    $response->header('Content-Type', 'text/plain');
    $response->end(serialize($result));
    $end = microtime(1);
    echo 'asyc: ', ($begin - $end), PHP_EOL;
    return;
}

function seqCall(swoole_http_request $request, swoole_http_response $response) {
    $begin = microtime(1);
    $cli = new Swoole\Coroutine\Http\Client('www.qq.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.qq.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->get('/');
    $result[] = $cli->body;
    $cli->close();


    $cli = new Swoole\Coroutine\Http\Client('www.163.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.163.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->get('/');
    $result[] = $cli->body;
    $cli->close();

    $response->header('Content-Type', 'text/plain');
    $response->end(serialize($result));
    $end = microtime(1);
    echo 'seq: ', ($begin - $end), PHP_EOL;
    return;
}