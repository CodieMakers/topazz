<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Clause;


class WhereClause extends Clause {

    protected $whereClause = "";
    protected $isHaving = false;

    public function where($key, $comparison, $glue = "AND") {
        if (!empty($this->whereClause)) {
            $this->whereClause .= " {$glue} ";
        }
        $this->whereClause .= "{$key} {$comparison} ?";
    }

    public function whereEquals($key, $glue = "AND") {
        $this->where($key, "EQUALS", $glue);
    }

    public function whereIn($key, $count, $glue = "AND") {
        if (!empty($this->whereClause)) {
            $this->whereClause .= " " . $glue . " ";
        }
        $this->whereClause .= "{$key} IN (" . join(', ', array_fill(0, $count - 1, '?')) . ")";
    }

    public function having() {
        $this->isHaving = true;
    }

    public function __toString(): string {
        return " " . ($this->isHaving ? "HAVING" : "WHERE") . " " . $this->whereClause;
    }
}