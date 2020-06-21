<?php

namespace App\Service;

use Minicli\App;

class CommandService implements MessageExtractionServiceInterface {
    use MessageExtractionTrait;

    protected $app;
    protected $list = [];

    /**
     * CommandService constructor.
     *
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        if($app->config->has('commands')) {
            $this->list = $app->config->commands;
        }
    }

    /**
     * Checks if received message is a known command
     *
     * @param String $raw
     * @return null|string
     */
    public function extract(String $raw) {
        $parts = explode(":", $raw, 3);
        $nick = explode("!", $parts[1])[0];
        $message = $parts[2];

        foreach($this->list as $command => $options) {
            if(strpos($message, '!'.$command) === 0) {
                if(!$this->_checkCooldown($command)) return null;
                $this->app->getPrinter()->out("User " . $nick . " COMMAND FOUND: " . $message . "\n", "dim");
                return $options['response'];
            }
        }
        return null;
    }
}