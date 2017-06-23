<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections;


use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Data\Collections\Maps\HashMap;

class Collectors {

    public static function toMap() {
        return function ($items) {
            return new HashMap($items);
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