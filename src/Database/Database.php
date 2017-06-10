<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use Topazz\Data\Collection\CollectionInterface;
use Topazz\Database\Statement\Statement;
use Topazz\Database\Statement\StatementInterface;

class Database {

    protected $connection;
    /** @var StatementInterface $statement */
    protected $statement;
    protected $entity = \stdClass::class;

    public function __construct($dsn, $username, $password) {
        $this->connection = new PDO($dsn, $username, $password);
    }

    public function setStatement(StatementInterface $statement) {
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
        return new Result($this->connection, $pdoStatement, $this->entity);
    }

    public function executeAll(CollectionInterface $statements) {
        return $statements->stream()->map(function (Statement $statement) {
            $this->setStatement($statement);
            return $this->execute();
        })->toList();
    }
}