<?php

require("vendor/autoload.php");

$loop = React\EventLoop\Factory::create();

$client = new Slack\RealTimeClient($loop);
$client->setToken('...');

// disconnect after first message
$client->on('message', function ($data) use ($client) {

	// we sometimes getting annoying messages from slackbot - e.g. on startup
	if($data["user"] == "U2HUETWVC") return ;

	$client->getDMById($data["channel"])->then(function (\Slack\DirectMessageChannel $channel) use ($client, $data) {
		$client->send("What do you mean with: '" . $data["text"] . "'?", $channel);
	});
});

$client->connect()->then(function () {
    echo "Connected!\n";
});

$loop->run();
