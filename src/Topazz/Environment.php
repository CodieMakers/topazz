<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

class Environment {

    protected static $env = [
        "ENV" => "installation"
    ];

    public static function get($key) {
        return self::$env[$key];
    }

    public static function set($key, $value) {
        self::$env[$key] = $value;
    }

    public static function isProduction() {
        return self::$env["ENV"] == "production";
    }

    public static function loadFromFile() {
        $filesystem = new Filesystem();
        if ($filesystem->exists(".env")) {
            (new Dotenv())->load(".env");
            self::$env = &$_ENV;
        }
    }
}