<?php

$server = new \Swoole\Server('127.0.0.1', 10000);

$server->set([
    'worker_num' => 2,
    'task_worker_num' => 4, // 不指定此项不能执行task任务
]);

$server->on('connect', function (swoole_server $server, $fd) {

});
$server->on('receive', function (swoole_server $server, $fd, $from_id, $data) {
    echo 'Receive data:', $data, PHP_EOL;
    $data = trim($data);
    $task_id = $server->task($data, 1);
    $server->send($fd, "分发任务，任务id为$task_id\n");
    $tasks[] = mt_rand(1000, 9999); //任务1
    $tasks[] = mt_rand(1000, 9999); //任务2
    $tasks[] = mt_rand(1000, 9999); //任务3
    var_dump($tasks);

//等待所有Task结果返回，超时为10s
    $results = $server->taskWaitMulti($tasks, 10.0);

    if (!isset($results[0])) {
        echo "任务1执行超时了\n";
    }
    if (isset($results[1])) {
        echo "任务2的执行结果为{$results[1]}\n";
    }
    if (isset($results[2])) {
        echo "任务3的执行结果为{$results[2]}\n";
    }
});
$server->on('close', function () {

});
$server->on('task', function (swoole_server $server, $task_id, $from_id, $data) {
    echo $from_id, 'Task receive data: ', $data, PHP_EOL;
    echo "#{$server->worker_id}\tonTask: [PID={$server->worker_pid}]: task_id=$task_id, data_len=".strlen($data).".".PHP_EOL;
    $server->finish($data);
});
$server->on('finish', function (swoole_server $server, $task_id, $data) {
    echo $task_id, 'task is finished, data: ', $data, PHP_EOL;
    //$server->send($task_id, 'task finished');
});

$server->on('workerStart', function ($serv, $worker_id) {
//    global $argv;
//    if($worker_id >= $serv->setting['worker_num']) {
//        swoole_set_process_name("php {$argv[0]}: task_worker");
//    } else {
//        swoole_set_process_name("php {$argv[0]}: worker");
//    }
});
$server->start();