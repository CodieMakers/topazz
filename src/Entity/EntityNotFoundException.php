<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;

use Topazz\TopazzApplicationException;


class EntityNotFoundException extends TopazzApplicationException {
    public function __construct() {
        parent::__construct('This entity was not found.');
    }
}