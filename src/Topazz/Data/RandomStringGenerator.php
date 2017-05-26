<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


class RandomStringGenerator {

    private static $alphabet;

    public static function generate(int $length) {
        if (is_null(self::$alphabet)) {
            self::defaultAlphabet();
        }
        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $string .= self::$alphabet[mt_rand(0, strlen(self::$alphabet) - 1)];
        }
        return $string;
    }

    public static function withoutSpecials() {
        self::$alphabet = implode(range('a', 'z')) .
            implode(range('A', 'Z')) .
            implode(range(0,9));
    }

    public static function defaultAlphabet() {
        self::$alphabet = implode(range('a', 'z')) .
            implode(range('A', 'Z')) .
            implode(range(0, 9)) .
            '$&@.,+-=_!?';
    }
}