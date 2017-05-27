<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


class UnsupportedMethodException extends ApplicationException {

    public function __construct() {
        parent::__construct(ApplicationException::UNSUPPORTED_METHOD);
    }
}