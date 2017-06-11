<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use PDOStatement;
use stdClass;
use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Data\Collection\Lists\ListInterface;

class Result {

    protected $connection;
    protected $statement;

    public function __construct(PDO $connection, PDOStatement $statement, string $className = stdClass::class) {
        $this->connection = $connection;
        $this->statement = $statement;
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $className);
    }

    public function all(): ListInterface {
        return new ArrayList($this->statement->fetchAll());
    }

    public function row() {
        return $this->statement->fetch();
    }

    public function raw() {
        $this->statement->setFetchMode(PDO::FETCH_ASSOC);
        return $this->statement->fetchAll();
    }

    public function count() {
        return $this->statement->rowCount();
    }

    public function lastInsertedId() {
        return $this->connection->lastInsertId();
    }
}