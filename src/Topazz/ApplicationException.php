<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use RuntimeException;

class ApplicationException extends RuntimeException {

    const NOT_INIT = "Application was not initialised yet!";
    const DATABASE_NO_QUERY = "There is no prepared query!";

    public function __construct(string $message = "There was an unidentified error during the application running process!") {
        parent::__construct($message, 0, null);
    }
}