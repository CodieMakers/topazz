<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


class ObjectCollection extends Collection {

    public function find($key) {
        return $this->filter(function ($item) use ($key) {
            return is_object($item) && in_array($key, get_object_vars($item));
        })->first();
    }
}