<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class Optional {

    private $item;

    public function __construct($item) {
        $this->item = $item;
    }

    public function orThrow(\Exception $exception) {
        if (is_null($this->item)) {
            throw $exception;
        }
        return $this->item;
    }

    public function orCall(callable $callable) {
        if (is_null($this->item)) {
            return call_user_func($callable);
        }
        return $this->item;
    }

    public function orGet($item) {
        return is_null($this->item)? $item : $this->item;
    }

    public function orNull() {
        return $this->orGet(null);
    }
}