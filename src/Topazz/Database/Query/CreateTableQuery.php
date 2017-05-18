<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


class CreateTableQuery extends Query {

    /**
     * @return string
     */
    public function getQuery(): string {
        return "CREATE TABLE IF NOT EXISTS " . $this->table . " (" . implode(", ", $this->columns) . ") COLLATE utf8_general_ci ENGINE InnoDB";
    }

    /**
     * @return array
     */
    public function getAttributes(): array {
        return [];
    }
}