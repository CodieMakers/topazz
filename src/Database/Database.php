<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use PDOException;
use PDOStatement;
use Topazz\Data\Collections\CollectionInterface;
use Topazz\Data\Collections\ListInterface;
use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Database\Statement\Statement;
use Topazz\Database\Statement\StatementInterface;

class Database {

    /** @var PDO $connection */
    protected $connection;
    /** @var PDOStatement $pdoStatement */
    protected $pdoStatement;
    /** @var StatementInterface $statement */
    protected $statement;
    protected $entity = \stdClass::class;

    public function __construct($dsn, $username, $password) {
        $this->connection = new PDO($dsn, $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function setStatement(StatementInterface $statement) {
        $this->statement = $statement;
        return $this;
    }

    public function setEntity(string $entity) {
        $this->entity = $entity;
        return $this;
    }

    public function inTransaction(): Database {
        if (!$this->connection->inTransaction()) {
            $this->connection->beginTransaction();
        }
        return $this;
    }

    public function execute(array $arguments = null) {
        try {
            $this->pdoStatement = $this->connection->prepare($this->statement->getQueryString());
            if (is_null($arguments)) {
                $arguments = $this->statement->getValues();
            }
            if (!is_null($arguments) && !empty($arguments)) {
                $this->pdoStatement->execute($arguments);
            } else {
                $this->pdoStatement->execute();
            }
        } catch (PDOException $exception) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            throw $exception;
        }
        $this->pdoStatement->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        return $this;
    }

    public function executeAll(CollectionInterface $statements) {
        $results = new ArrayList();
        $this->inTransaction();
        foreach ($statements as $statement) {
            $results->put($this->setStatement($statement)->execute()->raw());
        }
        $this->commit();
        return $results;
    }

    public function all(): ListInterface {
        return new ArrayList($this->pdoStatement->fetchAll());
    }

    public function row() {
        return $this->pdoStatement->fetch();
    }

    public function raw() {
        $this->pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        return $this->pdoStatement->fetchAll();
    }

    public function count() {
        return $this->pdoStatement->rowCount();
    }

    public function lastInsertedId() {
        return $this->connection->lastInsertId();
    }

    public function commit() {
        if ($this->connection->inTransaction()) {
            $this->connection->commit();
        }
    }
}