<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Table\Table;

class SelectStatement extends StatementWithWhereClause implements SelectInterface {

    protected $selectedColumns;
    protected $distinct = false;
    protected $joinClauses;
    protected $groupByColumns;
    protected $limitClause = "";

    public function __construct(Table $table) {
        parent::__construct($table);
        $this->selectedColumns = new ArrayList();
        $this->joinClauses = new ArrayList();
        $this->groupByColumns = new ArrayList();
    }

    public function distinct(): SelectInterface {
        $this->distinct = true;
        return $this;
    }

    public function all(): SelectInterface {
        $this->selectedColumns->putAll($this->table->getColumnNames());
        return $this;
    }

    public function columns(string... $columnNames): SelectInterface {
        $this->selectedColumns->putAll($columnNames);
        return $this;
    }

    public function groupBy(string... $columns): SelectInterface {
        $this->groupByColumns->putAll($columns);
        return $this;
    }

    public function limit(int $limit, int $offset = 0): SelectInterface {
        $this->limitClause = "LIMIT {$limit}";
        if ($offset > 0) {
            $this->limitClause .= " OFFSET {$offset}";
        }
        return $this;
    }

    public function join($table1, array $table2, string $joinType = "LEFT JOIN") {
        list($leftTable, $leftColumn) = $table2;
        $rightTable = $this->table->getName();
        if (is_array($table1)) {
            list($rightTable, $rightColumn) = $table1;
        } else {
            $rightColumn = $table1;
        }
        $this->joinClauses->put("$joinType $leftTable ON $leftTable.$leftColumn = $rightTable.$rightColumn");
        return $this;
    }

    public function getQueryString(): string {
        $selectExpression = "";
        $tableReference = "";
        $groupByClause = "";
        $whereClause = "";
        $orderByClause = "";

        // --------- SELECT
        if ($this->distinct) $selectExpression .= "DISTINCT ";
        $selectExpression .= $this->selectedColumns->stream()->join(', ');
        // --------- FROM
        $tableReference .= "{$this->table->getName()}";
        if ($this->joinClauses->length() > 0) $tableReference .= " " . $this->joinClauses->stream()->join(' ');
        // --------- GROUP BY
        if ($this->groupByColumns->length() > 0) $groupByClause = " GROUP BY " . $this->groupByColumns->stream()->join(', ');
        // --------- WHERE / HAVING
        if ($this->whereExpressions->length() > 0) $whereClause = !empty($groupByClause) ? " HAVING " : " WHERE ";
        $whereClause .= $this->whereExpressions->stream()->map(function (array $whereExpressionObject, int $index) {
            $expression = "";
            if ($index !== 0) {
                $expression = $whereExpressionObject["chain"] . " ";
            }
            return $expression . $whereExpressionObject["expression"];
        })->join(' ');
        // --------- ORDER BY

        return
            "SELECT {$selectExpression} FROM {$tableReference}{$groupByClause}{$whereClause}{$orderByClause}{$this->limitClause}";
    }
}