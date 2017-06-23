<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


class Optional {

    private $item;

    public function __construct($item = null) {
        $this->item = $item;
    }

    public function orThrow(\Exception $exception) {
        if (is_null($this->item)) {
            throw $exception;
        }
        return $this->item;
    }

    public function orCall(callable $callable) {
        return $this->item ?: call_user_func($callable);
    }

    public function orGet($item) {
        return $this->item ?: $item;
    }

    public function orNull() {
        return $this->item ?: null;
    }

    public function isNull() {
        return is_null($this->item);
    }
}