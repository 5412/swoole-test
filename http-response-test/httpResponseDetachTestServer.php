<?php
$http = new swoole_http_server('0.0.0.0', 8001, SWOOLE_PROCESS);

$http->set([
    'task_worker_num' => 1,
    'worker_num' => 1,
    'package_max_length' => 1024, // post 尺寸限制
    'upload_tmp_dir' => '/data/uploadfiles/',
    'http_parse_post' => false, // 自动将Content-Type为x-www-form-urlencoded的请求包体解析到POST数组
    'http_parse_cookie' => false, // 关闭Cookie解析，将在header中保留未经处理的原始的Cookies信息。

    // 目前支持gzip、br、deflate 三种压缩格式，
    //底层会根据浏览器客户端传入的Accept-Encoding头自动选择压缩方式。http-chunk不支持分段单独压缩, 已强制关闭压缩.
    'http_compression' => true,

    // 设置document_root并设置enable_static_handler为true后，
    //底层收到Http请求会先判断document_root路径下是否存在此文件，如果存在会直接发送文件内容给客户端，不再触发onRequest回调。
    'document_root' => '/data/webroot/example.com', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,

    //设置静态处理器的路径。 例如/static/test.jpg会判断是否存在$document_root/static/test.jpg，如果存在则发送文件内容，不存在返回404错误。
    "static_handler_locations" => ['/static', '/app/images'],
]);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) use ($http) {
    try {
        $response->detach();
        var_export($response->fd);
        $http->task(strval($response->fd));
    } catch (Exception $e) {
        $response->end($e->getMessage());
    }

});

$http->on('finish', function ($data)
{
    echo "task finish";
});

$http->on('task', function ($serv, $task_id, $worker_id, $data)
{
    $resp = Swoole\Http\Response::create($data);
    $resp->end("in task");

    echo "async task\n";
    $serv->finish('1'); // finish必须调用在ontask里
});

$http->start();