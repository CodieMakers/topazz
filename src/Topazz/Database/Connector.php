<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use PDOStatement;
use Topazz\ApplicationException;
use Topazz\Data\Collection;

class Connector {

    /** @var PDO $connection */
    private $connection;
    /** @var PDOStatement $statement */
    private $statement;
    /** @var Query $query */
    private $query;
    private $inTransaction = false;

    private function connect() {
        if (is_null($this->connection)) {
            $this->connection = new PDO(
                'mysql:host=' . getenv("DB_HOST") .
                '; dbname=' . getenv("DB_NAME") . ';',
                getenv("DB_USER"),
                getenv("DB_PASSWORD")
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function query(Query $query): Connector {
        $this->query = $query;
        return $this;
    }

    public function toggleTransaction(): Connector {
        $this->inTransaction = true;
        return $this;
    }

    /**
     * Prepare statement based on Query {@see Connector::query} and execute.
     * To execute multiple times with same query, just call this method with appropriate arguments.
     *
     * @param string     $className Entity::class
     * @param array|null $arguments
     *
     * @return Connector
     */
    public function run(string $className, array $arguments = null): Connector {
        if (is_null($this->query)) {
            throw new ApplicationException(ApplicationException::DATABASE_NO_QUERY);
        }
        $this->connect();
        if ($this->inTransaction && !$this->connection->inTransaction()) {
            $this->connection->beginTransaction();
        }
        $this->statement = $this->connection->prepare($this->query->getQuery());
        try {
            $this->statement->execute(is_null($arguments) ? $this->query->getAttributes() : $arguments);
        } catch (\PDOException $exception) {
            if ($this->connection->inTransaction()) {
                $this->inTransaction = false;
                $this->connection->rollBack();
            }
            throw $exception;
        }
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $className);
        return $this;
    }

    /**
     * If is in transaction, commit changes, otherwise do nothing
     *
     * @return Connector
     */
    public function commit() {
        if ($this->inTransaction) {
            $this->inTransaction = false;
            $this->connection->commit();
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function all() {
        return new Collection($this->statement->fetchAll());
    }

    /**
     * @return Entity|null
     */
    public function row() {
        return $this->statement->fetch();
    }

    /**
     * @return int
     */
    public function count() {
        return $this->statement->rowCount();
    }

    public function insertedId() {
        return $this->connection->lastInsertId();
    }
}