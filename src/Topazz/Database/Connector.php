<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use Topazz\Database\Statement\CreateTableStatement;
use Topazz\Database\Statement\CreateUserStatement;
use Topazz\Database\Statement\DropTableStatement;

class Connector {

    private static $username;
    private static $password;
    private static $dns;

    protected $connection;

    public function __construct() {
        self::$dns = "mysql:host=" . getenv("DB_HOST") . "; dbname=" . getenv("DB_NAME") . ";";
    }

    public static function setUser(string $username, string $password) {
        self::$username = $username;
        self::$password = $password;
    }

    public static function resetUser() {
        self::$username = getenv('DB_USER');
        self::$password = getenv('DB_PASSWORD');
    }

    public function connect() {
        return new Database(self::$dns, self::$username, self::$password);
    }
}