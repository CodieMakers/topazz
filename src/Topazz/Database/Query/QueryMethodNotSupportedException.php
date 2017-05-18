<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


use Topazz\ApplicationException;

class QueryMethodNotSupportedException extends ApplicationException {

    public function __construct() {
        parent::__construct("This method is not supported in this context!");
    }
}