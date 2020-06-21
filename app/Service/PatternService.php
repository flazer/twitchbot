<?php

namespace App\Service;

use Minicli\App;

class PatternService implements MessageExtractionServiceInterface {
    use MessageExtractionTrait;

    protected $app;
    protected $list = [];

    /**
     * PatternService constructor.
     *
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        if($app->config->has('patterns')) {
            $this->list = $app->config->patterns;
        }
    }

    /**
     * Checks if a pattern matches with received message
     *
     * @param String $raw
     * @return null|string
     */
    public function extract(String $raw) {
        $parts = explode(":", $raw, 3);
        $nick = explode("!", $parts[1])[0];
        $message = $parts[2];

        foreach($this->list as $key => $row) {
            $pattern = $row['pattern'];
            $matches = [];
            if(is_array($pattern)) {
                $arr = $pattern;
                $pattern = '';
                foreach($arr as $word) {
                    $pattern .= '(?=.*' . $word . ')';
                }
                $pattern = '/(?i)' . $pattern . '/';
            }
            preg_match_all($pattern, $message, $matches);
            if(!empty($matches[0])) {
                if(!$this->_checkCooldown($key)) return null;
                $this->app->getPrinter()->out("User " . $nick . " PATTERN FOUND: " . $pattern . "\n", "dim");
                return $row['response'];
            }
        }

        return null;
    }
}