# Twitch FAQ Bot

[Twitch FAQ Bot](https://github.com/minicli/minicli) is just another, small bot for Twitch (irc in general) to answer frequently asked questions automatically.
It's based on minicli, an experimental dependency-free toolkit for building CLI-only applications in PHP created by [@erikaheidi](https://twitter.com/erikaheidi).
Find out more about [minicli](https://github.com/minicli/minicli).

### Why Twitch FAQ Bot

When I stream my content on Twitch it happens a lot, that people are asking the same questions over and over again. I can not blame them, but it's a kind of frustrating
to repeat yourself over and over again. I searched for a solution in [Nightbot](https://nightbot.tv) and [Moobot](http://moo.bot/), but couldn't find any. So I wrote my own bot.
This bot is able to parse every written chatmessage and tries to find known patterns, so that it can answer with a predefined text.

### Requirements
 - PHP >= 7.3

## Getting Started

You'll need `php-cli` and [Composer](https://getcomposer.org/) to get started.

Download the repo and install all dependecies

```
composer install
```

You'll find a configuration example in app/Config/. Just rename the file and edit it:
```
cd twitchbot/app/Config
mv App.example.php App.php
```

Editing the config:
```php
return [
    'twitch' => [
       'host' => 'irc.chat.twitch.tv'
       'port' => 6667,
       'twitch_user' => 'TWITCH_USERNAME',
       'twitch_channel' => 'TWITCH_CHANNEL',
       'twitch_oauth' => 'TWITCH_OAUTH_TOKEN', //Get OauthToken here: https://twitchapps.com/tmi/
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

```

To connect to Twitch, you'll need an OAuth-Token. To do so, just click [here](https://twitchapps.com/tmi/) and follow the instructions.  
Replace `TWITCH_OAUTH_TOKEN` with the generated token and `TWITCH_USERNAME` with yours. `TWITCH_CHANNEL` must be replaced with the streamer's username 
the bot should join.

## Starting
Once the installation is finished, you can run it with `minicli`:

```
cd twitchbot
./minicli twitch
```

## Patterns

To add a FAQ, you just have to add a new entry in config's patterns section.
You can write your own regular expression pattern, or just add a list of words (as an array), which have to be in the received message, so the bot will answer with it's given response.
If you are not familar with writing regex, just use the first example in the pattern section.  
Every pattern has a cooldown. The value represents seconds.  
If the bot can't find any specification, it'll take 5 seconds as default.


## Commands

You can add a new command by just expanding the commands section. Just copy the ping example and edit, so that it belongs your needs.  
Don't write an exclamation mark in front. This is done automatically while checking the received chat message.
To trigger the ping-command just write !ping into the chat window.
Every command has a cooldown. The value represents seconds.  
If the bot can't find any specification, it'll take 5 seconds as default.

## Links  

Do you have an idea or problem? Want to build any kind of software?  
Contact the best problem-solving-company in the world: [Frozen Donkey](https://frozendonkey.com/).  
  
Thanks to [@erikaheidi](https://twitter.com/erikaheidi) for her awesome work on minicli! 

## Disclaimer
This project does not use or include any resource from Twitch.
Use this project at your own risc. The authors and contributors will not be able to provide any help if Twitch complains about usage or banning.

## Donations

If you like the project (or me) feel free to donate:  
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](http://paypal.me/flazer)

## Subscribe
Follow and subscribe on twitch: [https://www.twitch.tv/chrisfigge](https://www.twitch.tv/chrisfigge) 
