<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


class WhereClause {

    const WHERE_EQUALS = 0;
    const WHERE_LIKE = 1;
    const WHERE_IN = 2;
    const WHERE_BETWEEN = 3;
    const WHERE_GT = 4;
    const WHERE_LT = 5;
    const WHERE_GTE = 6;
    const WHERE_LTE = 7;
    const WHERE_EXISTS = 8;

    const GLUE_AND = 0;
    const GLUE_OR = 1;

    private $clause;
    private $not = false;
    private $glue = 0;
    private $values = [];

    public function add($column, $value, int $comparison, int $glue = 0) {
        $this->glue = $glue;
        $this->prepareClause();
        switch ($comparison) {
            case self::WHERE_LIKE:
                $this->clause .= $column . " LIKE ?";
                break;
            case self::WHERE_IN:
                $this->clause .= $column . " IN (";
                if ($value instanceof SelectQuery) {
                    $this->clause .= $value->getQuery();
                } elseif (is_array($value)) {
                    $this->clause .= array_fill(0, count($value) - 1, "?");
                } else {
                    throw new \InvalidArgumentException(sprintf("Invalid value, %s, inside IN clause.", $value));
                }
                $this->clause .= ")";
                break;
            case self::WHERE_EXISTS:
                $this->clause .= "EXISTS (";
                if ($value instanceof SelectQuery) {
                    $this->clause .= $value->getQuery() . ")";
                } else {
                    throw new \InvalidArgumentException("Invalid value inside EXISTS context!");
                }
                break;
            case self::WHERE_BETWEEN:
                $this->clause .= $column . " BETWEEN ? AND ?";
                break;
            case self::WHERE_GT:
                $this->clause .= $column . " > ?";
                break;
            case self::WHERE_LT:
                $this->clause .= $column . " < ?";
                break;
            case self::WHERE_GTE:
                $this->clause .= $column . " >= ?";
                break;
            case self::WHERE_LTE:
                $this->clause .= $column . " <= ?";
                break;
            default:
                $this->clause .= $column . " = ?";
                break;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        $this->values = array_merge($this->values, $value);
        $this->not = false;
    }

    public function not() {
        $this->not = true;
    }

    private function prepareClause() {
        if ($this->clause !== "") {
            $this->clause .= " " . ($this->glue == 0 ? "AND" : "OR") . " ";
        }
        if ($this->not) {
            $this->clause .= "NOT ";
        }
    }

    public function getWhereClause(bool $having = false) {
        return " " . ($having ? "HAVING" : "WHERE") . " " . $this->clause;
    }

    public function getAttributes() {
        return $this->values;
    }
}