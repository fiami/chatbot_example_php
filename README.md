This tutorial shows how to create a bot that used the Slack Real Time API and communicates vie websockets, so that it can run locally.

1. Create bot user in slack
===========================

A bot users needs to be registered in your slack instance first. Please visit https://<your slack team>.slack.com/apps/new/A0F7YS25R-bots and enter a name for your bot like "@tichy". Click "Add bot integration"

After creating copy the API Token - could look like: "xoxb-632474827434739-3rHJHKJSURJWILJAID..." - of course you could also upload an image

2. Install composer
===================

When starting with PHP, we need to install composer in order to load the framework. In newer ubuntu version you could get it from the repositories - order check the official website https://getcomposer.org/ for more details.

3. Install framework
====================

Execute "composer install" in your respository directory.

4. Create first basic bot
=========================

Open the file "bot_01_test.php" and add your api token:

```
$client->setToken('xoxb-...');
```

Then go your console and run the bot:

```
php ./bot_01_test.php
```

As soon as it is running, it will echo "Connected!" to the console. Once this appears, you bot user should also switch the status to "active" in Slack. If you start writing something now to the user directy, the message object will get printed to the console and looks like this:

```
Slack\Payload Object
(
    [data:protected] => Array
        (
            [type] => message
            [channel] => D2JANSGCT
            [user] => U0KGF0KM2
            [text] => Hello Tichy!
            [ts] => 1475404368.000002
            [team] => T0KGN74HE
        )
)

```

You can now see the options, you get with the request: tyoe of message, the channel it was sent via, the message itself etc.

5. Understand the code of the bot
=================================

The framwork is built with a message driven way, as you mith now from nodeJS. We first start to register messages on which it should react like

```
$client->on('message', function ($data) use ($client) {
	print_r( $data );
});
```

before it then connects to Slack and starts the main loop:

```
$client->connect()->then(function () {
    echo "Connected!\n";
});

$loop->run();
```

6. Answer to requests
=====================

In order to answer to requests, we need to use the message "send" of our $client object. Please notice, that we always need to pass a channel object to the send message, in order to send it back to the right discussion. This part is a bit confusing, because a) also direct communications between two people have a channel id, b) we are getting the right channel id always with the sent message, c) depending on the id of the channel, we need to use different methods and the id is the only things that helps us knowing which kind of channel we need to use.

For example the sample request above contains the channel id "D2JANSGCT" - which is a channel id for a direct communication (notice the capital "D" in the beginning). A normal channel would start with "C" like in "C1AJLKSI23". Everything else also follows this pattern like "U" in User ids (in our example: "U0KGF0KM2") or the "T" for indicating the team id ("T0KGN74HE").

In order send a message back to a direct conversation we can do the following:

```
$client->getDMById($data["channel"])->then(function (\Slack\DirectMessageChannel $channel) use ($client, $data) {
	$client->send("What do you mean with: '" . $data["text"] . "'?", $channel);
});
```

For a normal channel we would use:

```
$client->getChannelById($data["channel"])->then(function (\Slack\Channel $channel) use ($client, $data) {
	$client->send("What do you mean with: '" . $data["text"] . "'?", $channel);
});
```

To see an working example execute the file "bot_02_answer.php".

7. Create some value
====================

We now need to add some value to our bot. We could e.g. create a bot that gives us the data for a certain campaign for the last days. See a working example in "bot_03_campaign_stats.php".

If you ask the bot "How much money did the campaign <campaignid> made?" it will connect the platforms api in order to get this data. Please keep in mind, that we are not working with language processing, but just compare hard coded strings, so you really need to ask exactly that question.

8. Additional ideas
===================

You could make you bot more human, if you e.g. would use the service if www.api.ai in order to process the questions that got send to your bo, rather then hard code the messages. Also not only connecting to your system, but having an own storage, connecting to 3rd party APIs, etc. could be a good idea.
