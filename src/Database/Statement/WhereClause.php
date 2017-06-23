<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Database\Table\ColumnCollection;
use Topazz\Database\Table\Table;

class WhereClause {

    /** @var ArrayList $values */
    protected $values;
    /** @var ArrayList $whereExpressions */
    protected $whereExpressions;
    
    public function __construct() {
        $this->values = new ArrayList();
        $this->whereExpressions = new ArrayList();
    }

    public function where(string $columnName, $value, $not = false, $chain = "AND") {
        $expression = "";
        if ($this->whereExpressions->length() > 0) {
            $expression = "{$chain} ";
        }
        $not = $not ? " NOT" : "";
        if ($value instanceof StatementInterface) {
            $expression .= "{$columnName}{$not} IN ({$value->getQueryString()})";
            $this->values->putAll(...$value->getValues());
        } elseif (is_array($value)) {
            $expression .= "{$columnName}{$not} IN (" . join(",", array_fill(0, count($value) - 1, "?")) . ")";
            $this->values->putAll(...$value);
        } elseif (is_string($value) && strtolower($value) === "null") {
            $expression .= "{$columnName} IS{$not} NULL";
        } elseif (is_bool($value)) {
            $expression .= ($not ? "NOT " : "") . "{$columnName} IS " . ($value ? "TRUE" : "FALSE");
        } elseif (is_string($value)) {
            $value = htmlspecialchars($value);
            $expression .= "{$columnName}{$not} = ?";
            $this->values->put($value);
        } elseif (is_int($value) || is_float($value)) {
            $expression .= "{$columnName} = ?";
            $this->values->put($value);
        }
        $this->whereExpressions->put($expression);
        return $this;
    }

    public function whereLike(string $column, string $value, $not = false, $chain = "AND") {
        $prepend = "";
        if ($this->whereExpressions->length() > 0) {
            $prepend = "{$chain} ";
        }
        if ($not) {
            $column .= " NOT";
        }
        $this->whereExpressions->put($prepend . "{$column} LIKE ?");
        $this->values->put("%{$value}%");
    }

    public function whereExists(StatementInterface $statement, $not = false, $chain = "AND") {
        $this->whereExpressions->put(($not ? "NOT " : "") . "EXISTS ({$statement->getQueryString()})");
        $this->values->putAll(...$statement->getValues());
        return $this;
    }

    public function length(): int {
        return $this->whereExpressions->length();
    }
    
    public function join(): string {
        return $this->whereExpressions->stream()->toString(' ');
    }

    public function getValues(): array {
        return $this->values->toArray();
    }
}