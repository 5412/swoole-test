<?php
$chan = new \Swoole\Coroutine\Channel(3);

\Swoole\Coroutine::create(function () use ($chan) {
    for ($i = 0; $i<10; $i++) {
        \Swoole\Coroutine::sleep(1);
        $chan->push($i);
        echo 'push ', 'loop', $i, PHP_EOL;
    }
});

\Swoole\Coroutine::create(function () use ($chan) {
    for ($i = 0; $i<10; $i++) {
        \Swoole\Coroutine::sleep(0.1);
        $a = $chan->pop();
        echo 'pop ', $a, 'loop', $i, PHP_EOL;
    }
});

//swoole_event::wait();
