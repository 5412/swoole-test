<?php

$http = new swoole_http_server('0.0.0.0', 8000, SWOOLE_PROCESS);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
        return; // 后面结束执行
    }
    $response->cookie('x-token', 'd1d1d12d12d1d', 40, '/', '127.0.0.1', 1, 1);

    echo 'server', PHP_EOL;
    print_r($request->server);
    echo 'header', PHP_EOL;
    print_r($request->header);
    echo 'get', PHP_EOL;
    print_r($request->get);
    echo 'post', PHP_EOL;
    print_r($request->post);
    echo 'cookie', PHP_EOL;
    print_r($request->cookie);
    echo 'files', PHP_EOL;
    print_r($request->files);
    echo 'rawContent', PHP_EOL;
    print_r($request->rawContent());
    echo 'getData', PHP_EOL;
    print_r($request->getData());

    switch ($request->server['request_uri']) {
        default:
            if (! empty($request->files)) {
                foreach ($request->files as $file) {
                    if ($file['error'] == 0) {
                        move_uploaded_file($file['tmp_name'], './' . rand(1,200) .$file['name']);
                    }
                }
            }
            $response->end('Hello' . $request->server['request_uri']);
    }
});

$http->start();