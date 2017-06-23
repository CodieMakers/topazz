<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Symfony\Component\Dotenv\Dotenv;
use Topazz\Data\Filesystem;
use Topazz\Database\Connector;
use Topazz\Database\Database;

class Environment {

    protected static $env = [
        "ENV" => "production",
        "DB_HOST" => "localhost",
        "DB_NAME" => "topazz_db",
        "DB_USER" => "topazz_db"
    ];

    public static function get($key) {
        return self::$env[$key];
    }

    public static function set($key, $value) {
        self::$env[$key] = $value;
    }

    public static function has($key) {
        return isset(self::$env[$key]);
    }

    public static function isProduction() {
        return self::$env["ENV"] == "production";
    }

    public static function load() {
        $filesystem = new Filesystem();
        $file = $filesystem->getFilesystem()->get(".env");
        if ($file->exists()) {
            self::$env = array_merge(self::$env, (new Dotenv())->parse($file->getContent()));
        }
    }
}