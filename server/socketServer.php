<?php

$server = new \Swoole\Server('127.0.0.1', 10003, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->set([
    'worker_num' => 4,
    //'daemonize' => true,
    'backlog' => 128,

]);

//$server->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);
//$server->addlistener("172.24.42.134", 9503, SWOOLE_SOCK_TCP);
//$server->addlistener("0.0.0.0", 9506, SWOOLE_SOCK_UDP);
////UnixSocket Stream
//$server->addlistener("/Users/bjhl/sockets/myserv.sock", 0, SWOOLE_UNIX_STREAM);
////TCP + SSL
////$server->addlistener("127.0.0.1", 9505, SWOOLE_SOCK_TCP | SWOOLE_SSL);

$server->on('WorkerStart', function($serv, $workerId) {
    echo $workerId, PHP_EOL; //此数组中的文件表示进程启动前就加载了，所以无法reload
});
$server->on('start', function () use ($server) {
    echo 'manager pid is ', $server->manager_pid, PHP_EOL;
    echo 'master pid is ', $server->master_pid, PHP_EOL;

});

$server->on('connect', function ($server, $fd) {
    echo 'someone connect us' , $fd, PHP_EOL;
});

$server->on('packet', function () {
    echo 'someone packet us';
});

$server->on('receive', function ($server, $fd, $reactor_id, $data) {
    echo 'receive something', $data, PHP_EOL;
    $server->send($fd, "Swoole: {$data}");
    //$server->close($fd);
});

$server->on('close', function ($server, $fd) {
    echo 'someone close', $fd, PHP_EOL;
});

$server->start();