<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Database;
use Topazz\Database\Result;

abstract class Statement {

    protected $connection;
    protected $values;
    protected $entity;

    public function __construct(Database $connection) {
        $this->connection = $connection;
    }

    public function setEntity(string $entity) {
        $this->entity = $entity;
        return $this;
    }

    public function execute() {
        $statement = $this->connection->prepare((string)$this);
        $statement->execute($this->values);
        return new Result($statement);
    }

    abstract public function __toString(): string;
}