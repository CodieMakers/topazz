<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


class Utils {

    public static function camelize(string $string) {
        return preg_replace_callback('/[-_](\w)/', function ($matches) {
            return ucfirst($matches[1]);
        }, $string);
    }

    public static function now() {
        return (new \DateTime())->getTimestamp();
    }

    public static function isAssociativeArray($value): bool {
        return is_array($value) && !empty($value) && is_string(array_keys($value)[0]);
    }

    public static function isMoreThan(int $timestamp, int $than) {
        return round(abs($timestamp - self::now()) / 60, 2) >= $than;
    }
}