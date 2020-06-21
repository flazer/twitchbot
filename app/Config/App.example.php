<?php

return [
    'twitch' => [
       'host' => 'irc.chat.twitch.tv',
       'port' => 6667,
       'twitch_user' => 'TWITCH_USERNAME',
       'twitch_channel' => 'TWITCH_CHANNEL',
       'twitch_oauth' => 'TWITCH_OAUTH_TOKEN', //https://twitchapps.com/tmi/
    ],

    'patterns' => [
        [
            'pattern' => ['language', 'code'],
            'response' => "It is PHP, baby!",
            'cooldown' => 5
        ],
        [
            'pattern' => '/(?i)(?=.*script)(?=.*language)/',
            'response' => "PHP. Do you speak it?!",
            'cooldown' => 5
        ]
    ],

    'commands' => [
        'ping' => [
            'response' => 'pong!',
            'cooldown' => 5
        ],
        'donate' => [
            'response' => 'Awww. Thank you. Just go to: http://paypal.me/flazer',
            'cooldown' => 5
        ]
    ]
];