<?php

namespace App\Service;

use Minicli\App;

class ClientService {

    protected $app;
    protected $socket;
    protected $user;
    protected $oauth;
    protected $channel;
    protected $host;
    protected $port;

    /**
     * TwitchChatClient constructor.
     *
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app  = $app;
        $config     = $app->config;

        if($config->has('twitch')) {
            $this->host     = $config->twitch['host'];
            $this->port     = $config->twitch['port'];
            $this->user     = $config->twitch['twitch_user'];
            $this->oauth    = $config->twitch['twitch_oauth'];
            $this->channel  = $config->twitch['twitch_channel'];
        }
    }

    /**
     * Connect to server via specified credentials in config
     * @return null
     */
    public function connect() {
        if(!$this->user || !$this->oauth) {
            $this->app->getPrinter()->error("Missing 'twitch.user' and/or 'twitch.oauth' config settings.");
            return null;
        }

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(socket_connect($this->socket, $this->host, $this->port) === false) return null;

        $this->authenticate();
        $this->setUser();
        $this->joinChannel($this->channel);
    }

    /**
     * Authenticate via oauth-token set in config
     */
    public function authenticate() {
        $this->send(sprintf("PASS %s", $this->oauth));
    }

    /**
     * Set specified user
     */
    public function setUser() {
        $this->send(sprintf("NICK %s", $this->user));
    }

    /**
     * Join a specified channel
     *
     * @param String $channel
     */
    public function joinChannel(String $channel) {
        $this->send(sprintf("JOIN #%s", $channel));
    }

    /**
     * Return last error from socket
     *
     * @return int
     */
    public function getLastError() {
        return socket_last_error($this->socket);
    }

    /**
     * Check if socket is connected
     *
     * @return bool
     */
    public function isConnected() {
        return !is_null($this->socket);
    }

    /**
     * Read the socket, if connected
     *
     * @param int $size
     * @return null|string
     */
    public function read(int $size = 256) {
        if(!$this->isConnected()) return null;
        return socket_read($this->socket, $size);
    }

    /**
     * Send a message to channel
     *
     * @param String $message
     * @return int|null
     */
    public function talk(String $message) {
        return $this->send(
            sprintf(
                ':%s!%s@%s.tmi.twitch.tv PRIVMSG #%s :%s',
                $this->user,
                $this->user,
                $this->user,
                $this->channel,
                $message
            )
        );
    }

    /**
     * Send command/message
     *
     * @param String $message
     * @return int|null
     */
    public function send(String $message) {
        if(!$this->isConnected()) return null;
        return socket_write($this->socket, $message . "\n");
    }

    /**
     * Close socket
     */
    public function close() {
        socket_close($this->socket);
    }
}
