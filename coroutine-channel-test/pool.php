<?php

class RedisPool {
    protected $pool;
    function __construct($size = 10)
    {
        $this->pool = new \Swoole\Coroutine\Channel($size);
        for ($i = 0; $i < $size; $i++) {
            $redis = new \Swoole\Coroutine\Redis();
            $res = $redis->connect('127.0.0.1', 6379);
            if (false === $res) {
                throw new RuntimeException('failed to connect redis server');
            } else {
                $this->put($redis);
            }
        }
    }

    function put($redis)
    {
        if ($redis instanceof \Swoole\Coroutine\Redis) {
            $this->stats();
            $this->pool->push($redis);
        } else {
            throw new RuntimeException('must push a \Swoole\Coroutine\Redis implementation');
        }
    }
    
    function get()
    {
        return $this->pool->pop();
    }

    function stats()
    {
        var_export($this->pool->stats());
    }
}

//$server = new swoole_http_server('0.0.0.0', 9999);
//$server->on('WorkerStart', function () {
//    $pool = new RedisPool(10);
//
//    $redis = $pool->get();
//
//    $list = $redis->get('clientList');
//    var_dump($list);
//});
//
//$server->on('request', function ($request, $response) {
//    $response->end('hi ha hi');
//});
//
//$server->start();

go(function () {
    $pool = new RedisPool(10);

    $redis = $pool->get();

    $list = $redis->get('clientList');
    $pool->stats();

    //var_dump($list);
});
