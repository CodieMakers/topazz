<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use Topazz\Database\Statement\CreateTableStatement;
use Topazz\Database\Statement\CreateUserStatement;
use Topazz\Database\Statement\DropTableStatement;
use Topazz\Database\Statement\DropUserStatement;
use Topazz\Database\Statement\SelectStatement;

class Database extends PDO {

    protected $entity;

    public function __construct($dsn, $username, $password) {
        $options = $this->getDefaultOptions();
        parent::__construct($dsn, $username, $password, $options);
    }

    private function getDefaultOptions() {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS
        ];
    }

    public function select($what = ["*"]) {
        return new SelectStatement($this, $what);
    }

    public function createTable(string $table) {
        return new CreateTableStatement($this, $table);
    }

    public function dropTable(string $table) {
        return new DropTableStatement($this, $table);
    }

    public function createUser(string $username, string $password) {
        return new CreateUserStatement($this, $username, $password);
    }

    public function dropUser(string $username) {
        return new DropUserStatement($this, $username);
    }
}