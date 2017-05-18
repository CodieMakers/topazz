<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


class UpdateQuery extends Query {

    private $values = [];

    public function __construct(array $data, $table) {
        parent::__construct(array_keys($data), $table);
        $this->values = array_values($data);
    }

    /**
     * @return string
     */
    public function getQuery(): string {
        return "UPDATE " . $this->table . " SET " . implode("=?, ", $this->columns) . "=?" . $this->where->getWhereClause();
    }

    public function getAttributes(): array {
        return array_merge($this->values, $this->where->getAttributes());
    }

}