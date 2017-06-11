<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Topazz\Environment;

class RandomStringGenerator {

    private static $alphabet;

    public static function generate(int $length) {
        if (is_null(self::$alphabet)) {
            self::$alphabet = implode(range('a', 'z')) .
                implode(range('A', 'Z')) .
                implode(range(0, 9)) .
                '$&@.,+-=_!?';
        }
        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $string .= self::$alphabet[mt_rand(0, strlen(self::$alphabet) - 1)];
        }
        if (Environment::has('SECRET')) {
            $string .= "." . base64_encode(Environment::get('SECRET'));
        }
        return $string;
    }

    public static function withoutSpecials(int $length) {
        self::$alphabet = implode(range('a', 'z')) .
            implode(range('A', 'Z')) .
            implode(range(0,9));
        $result = self::generate($length);
        self::$alphabet = null;
        return $result;
    }
}