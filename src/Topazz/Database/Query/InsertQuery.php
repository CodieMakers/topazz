<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


class InsertQuery extends Query {

    private $values = [];

    public function __construct(array $data = [], $table) {
        parent::__construct(array_keys($data), $table);
        $this->values = array_values($data);
    }

    /**
     * @return string
     */
    public function getQuery(): string {
        return "INSERT INTO " . $this->table . " (" . implode(", ", $this->columns) .
            ") VALUES (" . implode(", ", array_fill(0, count($this->values) -1, "?")) . ")";
    }

    public function getAttributes(): array {
        return array_merge($this->values, $this->where->getAttributes());
    }

    public function where($column, $value, int $comparison = 0, int $glue = 0, bool $not = false) {
        throw new QueryMethodNotSupportedException();
    }

    public function whereNot($column, $value, $comparison, int $glue = 0) {
        throw new QueryMethodNotSupportedException();
    }

    public function orderBy($column, int $order = 0) {
        throw new QueryMethodNotSupportedException();
    }

    public function groupBy($columns) {
        throw new QueryMethodNotSupportedException();
    }

    public function join($leftTable, $rightTable, $leftColumn, $rightColumn, int $joinType = 0) {
        throw new QueryMethodNotSupportedException();
    }
}