<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Clause\WhereClause;

abstract class BasicStatement extends Statement {

    protected $whereClause;

    public function __construct() {
        parent::__construct();
        $this->whereClause = new WhereClause();
    }

    public function where(string $key, $comparison, $value, $glue = "AND") {
        $this->values->put($value);
        $this->whereClause->where("{$key} {$comparison} ?", $glue);
        return $this;
    }

    public function whereLike(string $key, string $value, $not = false, $glue = "AND") {
        return $this->where($key, ($not ? "NOT ": "") . "LIKE", $value, $glue);
    }

    public function whereNotLike(string $key, string $value, $glue = "AND") {
        return $this->whereLike($key, $value, true, $glue);
    }

    public function whereExists(Statement $substatement, $not = false, $glue = "AND") {
        $this->values->putAll($substatement->getValues());
        $this->whereClause->where(($not ? "NOT ": "") . "EXISTS (" . $substatement->getQueryString() . ")", $glue);
        return $this;
    }

    public function whereNotExists(Statement $statement, $glue = "AND") {
        return $this->whereExists($statement, true, $glue);
    }

    public function whereIn(string $key, $values, $not = false, $glue = "AND") {
        if ($values instanceof Statement) {
            $this->values->putAll($values->getValues());
            $statement = $values->getQueryString();
        } else {
            $this->values->putAll($values);
            $statement = join(", ", array_fill(0, count($values) - 1, "?"));
        }
        $this->whereClause->where("{$key} " . ($not ? "NOT ": "") . "IN (" . $statement . ")", $glue);
        return $this;
    }

    public function whereNotIn(string $key, $values, $glue = "AND") {
        return $this->whereIn($key, $values, true, $glue);
    }
}