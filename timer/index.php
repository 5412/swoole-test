<?php
echo microtime(1), 'outside', PHP_EOL;
echo 1, PHP_EOL;
Swoole\Event::defer(function () {
    echo microtime(1), 'inside defer', PHP_EOL;
    echo "hello\n";
});
echo 2, PHP_EOL;
echo microtime(1), 'outside', PHP_EOL;
$count  = new \Swoole\Atomic(0);
Swoole\Timer::tick(1000, function ($timer_id, $a, $b) use ($count) {
    echo microtime(1), 'inside tick', PHP_EOL;
    echo $count->get(), PHP_EOL;
//    if ($count->get() === 10) {
//        \Swoole\Timer::clear($timer_id);
//    } // 本次清除本次循环会执行完
    echo 'timer: ', $timer_id, ' start at: ', microtime(1), PHP_EOL;
    $c = $count->get();
    Swoole\Timer::tick(3000, function () use ($c) {
        echo microtime(1), 'inside tick2', PHP_EOL;
        echo $c, " inside loop.\n";
        $args = func_get_args();
        \Swoole\Timer::clear($args[0]);
    });
    echo 'a: ', $a, ' b: ', $b, PHP_EOL;
    if ($count->get() === 3) {
        \Swoole\Timer::clear($timer_id);
    }
    $count->add(1);
    echo microtime(1), 'inside tick', PHP_EOL;
}, 1, 2);
echo microtime(1), 'outside', PHP_EOL;
sleep(10); // 阻塞了定时器的触发
echo microtime(1), 'outside', PHP_EOL;

