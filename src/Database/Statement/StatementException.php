<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\TopazzApplicationException;

class StatementException extends TopazzApplicationException {

    public function __construct($string) {
        parent::__construct($string);
    }
}