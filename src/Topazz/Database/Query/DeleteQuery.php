<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


class DeleteQuery extends Query {

    public function __construct(string $table) {
        parent::__construct([], $table);
    }

    /**
     * @return string
     */
    public function getQuery(): string {
        return "DELETE FROM " . $this->table . $this->where->getWhereClause();
    }

    public function getAttributes(): array {
        return $this->where->getAttributes();
    }
}