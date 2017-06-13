<?php

require("vendor/autoload.php");

$loop = React\EventLoop\Factory::create();

$client = new Slack\RealTimeClient($loop);
$client->setToken('...');

// disconnect after first message
$client->on('message', function ($data) use ($client) {

	print_r( $data );

});

$client->connect()->then(function () {
    echo "Connected!\n";
});

$loop->run();
