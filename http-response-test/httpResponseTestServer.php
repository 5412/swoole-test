<?php

$http = new swoole_http_server('0.0.0.0', 8000, SWOOLE_PROCESS);

$http->set([
    'http_compression' => true,
    'http_gzip_level' => 1,
]);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    try {
        if ($request->server['request_uri'] == '/favicon.ico') {
            $response->status(404);
            $response->end();
            return; // 后面结束执行
        }
        switch ($request->server['request_uri']) {
            case '/' :
                $response->header('x-user-token', '1f1ff1f1f1ff1f1f1f1ff1');
                $response->cookie('x-token', 'd1d1d12d12d1d', 40, '/', '127.0.0.1', 1, 1);
                $response->status(200);
                //$response->gzip(1); // 4.1.0及以上已废弃此函数 使用http_compression替代
                $response->end('hello world');
                break;
            case '/sendfile' :
                $response->header('Content-Type', 'application/text');
                $response->header('Content-Disposition', 'attachment; filename="test.txt"');
                $response->sendfile('./15test.txt', 1, 3); // filename offset length
                break;
            case '/write' :
                $response->write('1');
                $response->write(rand(1,2000));
                $response->write(md5('s'));
                $response->write('hi ha ha ha');
                break;
        }
    } catch (Exception $e) {
        $response->end($e->getMessage());
    }

});

$http->start();