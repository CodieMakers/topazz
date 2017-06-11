<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


class ConfigurationNotFoundException extends TopazzApplicationException {

    /**
     * ConfigurationNotFoundException constructor.
     */
    public function __construct(string $configKey) {
        parent::__construct(sprintf("There is no configuration like %s", $configKey));
    }
}