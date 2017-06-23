<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Config;


use Topazz\TopazzApplicationException;

class ConfigurationNotFoundException extends TopazzApplicationException {
    
    public function __construct(string $configKey) {
        parent::__construct(sprintf("There is no configuration like %s", $configKey));
    }
}