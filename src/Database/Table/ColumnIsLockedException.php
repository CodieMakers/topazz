<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


use Exception;

class ColumnIsLockedException extends Exception {
    public function __construct() {
        parent::__construct('You can not edit this column, it is already locked.');
    }
}