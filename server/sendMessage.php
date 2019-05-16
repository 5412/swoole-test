<?php

$serv = new \Swoole\Server('127.0.0.1', 10000);
$serv->set(array(
    'worker_num' => 2,
    'task_worker_num' => 2,
));
$serv->on('pipeMessage', function($serv, $src_worker_id, $data) {
    echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
});
$serv->on('task', function ($serv, $task_id, $reactor_id, $data){
    var_dump($task_id, $reactor_id, $data);
});
$serv->on('finish', function ($serv, $fd, $reactor_id){

});
$serv->on('receive', function (swoole_server $serv, $fd, $reactor_id, $data) {
    if (trim($data) == 'task')
    {
        $serv->task("async task coming");
    }
    else
    {
        $worker_id = 1 - $serv->worker_id;
        $serv->sendMessage("hello task process", $worker_id);
        $serv->send($fd, 'can task');
    }
});

$serv->start();