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
use Topazz\Database\Query\Query;

class Connector {

    /** @var PDO $connection */
    private $connection;
    /** @var PDOStatement $statement */
    private $statement;
    /** @var Query $query */
    private $query;

    private function connect() {
        if (is_null($this->connection)) {
            $this->connection = new PDO(
                'mysql:host=' . getenv("DB_HOST") .
                '; dbname=' . getenv("DB_NAME") .
                '; charset=utf-8',
                getenv("DB_USER"),
                getenv("DB_PASSWORD")
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this;
    }

    public function query(Query $query) {
        $this->query = $query;
        return $this;
    }

    /**
     * Prepares statement based on Query {@see Connector::query} and executes.
     *
     * @return Connector
     */
    public function run() {
        if (is_null($this->query)) {
            throw new ApplicationException(ApplicationException::DATABASE_NO_QUERY);
        }
        $this->connect();
        $this->statement = $this->connection->prepare($this->query->getQuery());
        $this->statement->execute($this->query->getAttributes());
        $this->statement->setFetchMode(PDO::FETCH_CLASS, Result::class);
        return $this;
    }

    /**
     * @return ResultSet
     */
    public function all() {
        return new ResultSet($this->statement->fetchAll());
    }

    /**
     * @return Result|null
     */
    public function row() {
        return $this->statement->fetch();
    }
}