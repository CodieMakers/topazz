<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use Topazz\Data\Collection;
use Topazz\Database\Statement\AbstractStatement;
use Topazz\Database\Statement\CreateTable;
use Topazz\Database\Statement\CreateUser;
use Topazz\Database\Statement\Delete;
use Topazz\Database\Statement\DropTable;
use Topazz\Database\Statement\DropUser;
use Topazz\Database\Statement\Insert;
use Topazz\Database\Statement\Select;
use Topazz\Database\Statement\Statement;
use Topazz\Database\Table\Table;

class Database {

    protected $connection;
    /** @var Statement $statement */
    protected $statement;
    protected $entity = \stdClass::class;

    public function __construct($dsn, $username, $password) {
        $this->connection = new PDO($dsn, $username, $password);
    }

    public function setStatement(Statement $statement) {
        $this->statement = $statement;
        return $this;
    }

    public function setEntity(string $entity) {
        $this->entity = $entity;
        return $this;
    }

    public function execute() {
        $pdoStatement = $this->connection->prepare($this->statement->getQueryString());
        $pdoStatement->execute($this->statement->getValues());
        return new Result($pdoStatement, $this->entity);
    }

    public function executeAll(Collection $statements) {
        return $statements->map(function (Statement $statement) {
            $this->setStatement($statement);
            return $this->execute();
        });
    }

    public static function select($what = ["*"]) {
        return new Select($what);
    }

    public static function insert($what = []) {
        return new Insert($what);
    }

    public static function update(string $table) {

    }

    public static function delete(string $from) {
        return new Delete($from);
    }

    public static function custom(string $query) {
        return new AbstractStatement($query);
    }
}