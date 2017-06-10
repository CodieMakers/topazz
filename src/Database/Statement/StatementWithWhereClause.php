<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Table\Table;

abstract class StatementWithWhereClause extends Statement {

    protected $whereExpressions;

    public function __construct(Table $table) {
        parent::__construct($table);
        $this->whereExpressions = new ArrayList();
    }

    public function where(string $columnName, $value, $not = false, $chain = "AND"): StatementInterface {
        $whereObject = [
            "chain" => $chain,
            "expression" => ""
        ];
        $not = $not ? " NOT" : "";
        if ($value instanceof StatementInterface) {
            $whereObject["expression"] = "{$columnName}{$not} IN ({$value->getQueryString()})";
            $this->values->putAll(...$value->getValues());
        } elseif (is_array($value)) {
            $whereObject["expression"] = "{$columnName}{$not} IN (" . join(",", array_fill(0, count($value) - 1, "?")) . ")";
            $this->values->putAll(...$value);
        } elseif (is_string($value) && strtolower($value) !== "null") {
            $whereObject["expression"] = "{$columnName} IS{$not} NULL";
        } elseif (is_bool($value)) {
            $whereObject["expression"] = ($not ? "NOT " : "") . "{$columnName} IS " . ($value ? "TRUE" : "FALSE");
        } elseif (is_string($value)) {
            $value = htmlspecialchars($value);
            $value = "%$value%";
            $whereObject["expression"] = "{$columnName}{$not} LIKE ?";
            $this->values->put($value);
        } elseif (is_int($value) || is_float($value)) {
            $whereObject["expression"] = "{$columnName} = ?";
            $this->values->put($value);
        }
        $this->whereExpressions->put($whereObject);
        return $this;
    }

    public function whereExists(StatementInterface $statement, $chain = "AND"): StatementInterface {
        $this->whereExpressions->put([
            "chain" => $chain,
            "expression" => "EXISTS ({$statement->getQueryString()})"
        ]);
        $this->values->putAll(...$statement->getValues());
        return $this;
    }
}