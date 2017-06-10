<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use Topazz\Database\Statement\CreateTable;
use Topazz\Database\Statement\CreateUser;
use Topazz\Database\Statement\DropTable;
use Topazz\Environment;

class Connector {

    private static $username;
    private static $password;

    public static function setUser(string $username, string $password) {
        self::$username = $username;
        self::$password = $password;
    }

    public static function resetUser() {
        self::$username = Environment::get('DB_USER');
        self::$password = Environment::get('DB_PASSWORD');
    }

    public static function connect() {
        $dbName = Environment::get("DB_NAME");
        return new Database(
            "mysql:host=" . Environment::get("DB_HOST") . ";" . (!is_null($dbName) ? " dbname={$dbName};" : ""),
            self::$username,
            self::$password
        );
    }
}