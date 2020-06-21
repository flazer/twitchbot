<?php

namespace App\Command\Twitch;

use App\Service\ClientService;
use App\Service\CommandService;
use App\Service\PatternService;
use Minicli\Command\CommandController;

class DefaultController extends CommandController {

    protected $clientService    = null;
    protected $commandService   = null;
    protected $patternService   = null;

    /**
     * Entrypoint.
     * Called from miniCli
     */
    public function handle() {
        $this->getPrinter()->info("Starting Bot...");
        $this->_init();

        $this->clientService->connect();

        if(!$this->clientService->isConnected()) {
            $this->getPrinter()->error("Connection failed.");
            return;
        }

        $this->getPrinter()->info("Connected.\n");

        //Le Looping Louie
        while(true) {
            $content = $this->clientService->read(512);
            $message = null;

            //is it an internal ping?
            if(strstr($content, 'PING')) {
                $this->clientService->send('PONG :tmi.twitch.tv');
                continue;
            }

            if(strstr($content, 'PRIVMSG')) {
                $this->printMessage($content);

                //Search for commands
                $message = $this->commandService->extract($content);
                if($message) {
                    $this->clientService->talk($message);
                    continue;
                }

                //Search for patterns
                $message = $this->patternService->extract($content);
                if($message) {
                    $this->clientService->talk($message);
                    continue;
                }
                continue;
            }

            $this->getPrinter()->out($content . "\n", "dim");
            sleep(0.1);
        }
    }



    /**
     * Dump a message into cli
     *
     * @param String $raw
     */
    public function printMessage(String $raw) {
        $parts = explode(":", $raw, 3);
        $nick = explode("!", $parts[1])[0];
        $message = $parts[2];
        $logStyle = "info";

        if($nick === $this->getApp()->config->twitch_user) {
            $logStyle = "info_alt";
        }

        $this->getPrinter()->out($nick, $logStyle);
        $this->getPrinter()->out(': ');
        $this->getPrinter()->out($message);
        $this->getPrinter()->newline();
    }

    /**
     * Init services
     */
    private function _init() {
        $this->clientService    = new ClientService($this->getApp());
        $this->commandService   = new CommandService($this->getApp());
        $this->patternService   = new PatternService($this->getApp());
    }
}