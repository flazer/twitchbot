<?php
namespace App\Service;

trait MessageExtractionTrait {

    /**
     * Checks if command has an active cooldown
     *
     * @param String $key
     * @return bool
     */
    private function _checkCooldown(String $key) {
        $cooldown = (isset($this->list[$key]['cooldown'])) ? intval($this->list[$key]['cooldown']) : 5;
        if(isset($this->list[$key]['lastUsage'])) {
            if(time() - $this->list[$key]['lastUsage'] <= $cooldown) return false;
        }
        $this->list[$key]['lastUsage'] = time();
        return true;
    }
}