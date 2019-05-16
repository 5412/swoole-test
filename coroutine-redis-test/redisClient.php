<?php

const REDIS_SERVER_HOST = '127.0.0.1';
const REDIS_SERVER_PORT = 6379;


//go(function () {
//    $redis = new Swoole\Coroutine\Redis();
//    $res = $redis->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
//
//    $redis->setDefer();
//    $redis->set('key1', 'value');
//    $redis2 = new Swoole\Coroutine\Redis();
//    $redis2->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
//    $redis2->setDefer();
//    $redis2->get('key1');
//    $result1 = $redis->recv();
//    $result2 = $redis2->recv();
//
//    var_dump($result1, $result2);
//});
//
//go(function () {
//    //co::sleep(1);
//    $redis = new Swoole\Coroutine\Redis();
//    $redis->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
//    $redis->setDefer();
//    $redis->set('key1', 'value1');
//    $redis->get('key1');
//    $result1 = $redis->recv();
//    $result2 = $redis->recv();
//
//    var_dump($result1, $result2);
//});

go(function () {
    $redis = new Swoole\Coroutine\Redis();
    $res = $redis->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    if (!$res) {
        echo 'Can\'t connect host' . REDIS_SERVER_HOST . ':' . REDIS_SERVER_PORT. PHP_EOL;
        return;
    }
    //$redis->setDefer();
    $value = $redis->get('key1');
    $redis->lPush('l1', 'A', 'B', 'C');
    $list = $redis->lGet('l1', 2);
    $list = $redis->lRange('l1', 0, -1);
    //$value = $redis->recv();
    var_dump($list);

});

echo 'main script', PHP_EOL;
