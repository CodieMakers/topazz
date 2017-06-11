<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Data\Collection\Map\Map;

abstract class Collection {

    public static function toMap() {
        return function ($items) {
            return new Map($items);
        };
    }

    public static function toList() {
        return function ($items) {
            return new ArrayList($items);
        };
    }

    public static function toString($glue = '') {
        return function ($items) use ($glue) {
            return join($glue, $items);
        };
    }
}