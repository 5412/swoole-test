<?php

$server = new swoole_http_server('127.0.0.1', 8989);
$server->set([
    'worker_num' => 5,
    'task_worker_num' => 2,
]);

$server->on('task', function (swoole_server $server, $task_id, $worker_id, $data) {
    echo "#{$server->worker_id}\tonTask: worker_id={$worker_id} | {$server->worker_id}, task_id=$task_id\n";
    $closeFdArrary = $server->heartbeat();
    var_export($closeFdArrary);
    if ($server->worker_id == 1) {
        sleep(1);
    }
    return $data;

});

$server->on('request', function (swoole_http_request $request, swoole_http_response $response) use ($server) {
    $tasks[0] = 'hello world';
    $tasks[1] = [
        'data' => 1234,
        'code' => 200,
    ];
    $socket = $server->getSocket();
    if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)) {
        echo 'Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
    }
    $result = $server->taskCo($tasks, 0.5);
    $response->end('Test End, Result: ' . var_export($result, 1));
});

$server->start();