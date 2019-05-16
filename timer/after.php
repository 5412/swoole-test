<?php

echo microtime(1), 'outside', PHP_EOL;
$str = 'solar';
Swoole\Timer::after(1000, function() use ($str) {
    echo microtime(1), 'inside', PHP_EOL;
    echo microtime(1), "timeout, $str\n";
    //co::sleep(10);
    sleep(1);
    echo microtime(1), 'inside', PHP_EOL;
});
echo microtime(1), 'outside', PHP_EOL;
sleep(2); // 阻塞3影响了定时器的触发
echo microtime(1), 'outside', PHP_EOL;