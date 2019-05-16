<?php

use Swoole\Coroutine\MySQL;

$mysqlConfig = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '',
    'database' => 'test',
    'fetch_mode' => true,
];

go(function () use ($mysqlConfig) {
    $swoole_mysql = new MySQL();
    $rs = $swoole_mysql->connect($mysqlConfig);
    if ($rs === false) {
        echo $swoole_mysql->connect_error, PHP_EOL;
        echo 'Connect failed', PHP_EOL;
        return;
    }
//    $sql = "select name,tel from market_client where name='solar'";
//
//    $sql = $swoole_mysql->escape($sql);
//    var_dump($sql); //  "select name,tel from market_client where name=\'solar\'"
//    $res = $swoole_mysql->query($sql);
//    var_dump($res);

//    $redis = new Swoole\Coroutine\Redis();
//    $redis->connect('127.0.0.1', 6379);
//    var_dump($res);
//    var_dump(serialize($res));
//
//    $res = $redis->set('client1', serialize($res));
//    var_dump($res);
//    var_dump($redis->get('client1'));

//    $stmt = $swoole_mysql->prepare("select * from market_client limit 2");
//    $stmt->execute();
//    while ($ret = $stmt->fetch()) {
//        var_dump($ret);
//    }

    $stmt = $swoole_mysql->prepare('CALL reply(?)');
    if ($stmt) {
        $stmt->execute(['solar']);
        do {
            echo '12';
            $res = $stmt->fetchAll();
            var_dump(json_encode($res));
            echo PHP_EOL;
        } while ($res = $stmt->nextResult());
        var_dump($stmt->affected_rows);
    } else {
        echo $stmt->error;
    }

//    $res = $swoole_mysql->query("call reply('solar')");
//    var_dump($res);


});
