<?php

echo 'main script', PHP_EOL;

go(function () {
    $cli = new Swoole\Coroutine\Http\Client("localhost", 9502);
    $ret = $cli->upgrade("/");
    var_dump($ret);
    echo $ret, PHP_EOL;
    if ($ret) {
        $cli->push("hello");
        echo '1', PHP_EOL;
        var_dump($cli->recv());
        co::sleep(0.1);
        while (1) {
            var_dump($cli->recv());
        }
    }
});
