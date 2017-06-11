<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


class StringUtils {

    public static function camelize(string $string) {
        return preg_replace_callback('/[-_](\w)/', function ($matches) {
            return ucfirst($matches[1]);
        }, $string);
    }
}