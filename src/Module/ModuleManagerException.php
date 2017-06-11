<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\TopazzApplicationException;

class ModuleManagerException extends TopazzApplicationException {

    public function __construct($message) {
        parent::__construct($message);
    }
}