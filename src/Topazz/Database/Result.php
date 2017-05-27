<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use Topazz\Data\Collection;

class Result {

    protected $statement;

    public function __construct(\PDOStatement $statement, string $className = \stdClass::class) {
        $this->statement = $statement;
        $this->statement->setFetchMode(\PDO::FETCH_CLASS, $className);
    }

    public function all() {
        return new Collection($this->statement->fetchAll());
    }

    public function row() {
        return $this->statement->fetch();
    }

    public function raw() {
        $this->statement->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->statement->fetchAll();
    }

    public function count() {
        return $this->statement->rowCount();
    }
}