<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Table\Table;
use Topazz\TopazzApplicationException;

class SelectStatement extends Statement {

    protected $table;
    protected $selectedColumns;
    protected $distinct = false;
    protected $joinClauses;
    protected $groupByColumns;
    protected $whereClause;
    protected $orderByClause;
    protected $limitClause = "";

    public function __construct(string... $columnNames) {
        parent::__construct();
        $this->selectedColumns = new ArrayList($columnNames);
        if ($this->selectedColumns->length() === 0) {
            $this->selectedColumns->put('*');
        }
        $this->joinClauses = new ArrayList();
        $this->groupByColumns = new ArrayList();
        $this->whereClause = new WhereClause();
        $this->orderByClause = new ArrayList();
    }

    public function distinct(): SelectStatement {
        $this->distinct = true;
        return $this;
    }

    public function from(string $table): SelectStatement {
        $this->table = $table;
        return $this;
    }

    public function groupBy(string... $columns): SelectStatement {
        $this->groupByColumns->putAll($columns);
        return $this;
    }

    public function where(string $columnName, $value): SelectStatement {
        $this->whereClause->where($columnName, $value);
        return $this;
    }

    public function orWhere(string $columnName, $value): SelectStatement {
        $this->whereClause->where($columnName, $value, false, "OR");
        return $this;
    }

    public function whereNot(string $columnName, $value): SelectStatement {
        $this->whereClause->where($columnName, $value, true);
        return $this;
    }

    public function orWhereNot(string $columnName, $value): SelectStatement {
        $this->whereClause->where($columnName, $value, true, "OR");
        return $this;
    }

    public function whereExists(StatementInterface $statement): SelectStatement {
        $this->whereClause->whereExists($statement);
        return $this;
    }

    public function whereNotExists(StatementInterface $statement): SelectStatement {
        $this->whereClause->whereExists($statement, true);
        return $this;
    }

    public function limit(int $limit, int $offset = 0): SelectStatement {
        $this->limitClause = "LIMIT {$limit}";
        if ($offset > 0) {
            $this->limitClause .= " OFFSET {$offset}";
        }
        return $this;
    }

    public function join($table1, array $table2, string $joinType = "LEFT JOIN"): SelectStatement {
        list($leftTable, $leftColumn) = $table2;
        $rightTable = $this->table;
        if (is_array($table1)) {
            list($rightTable, $rightColumn) = $table1;
        } else {
            $rightColumn = $table1;
        }
        $this->joinClauses->put("$joinType $leftTable ON $leftTable.$leftColumn = $rightTable.$rightColumn");
        return $this;
    }

    public function orderBy($columnNames, $order): SelectStatement {
        if (!is_array($columnNames)) {
            $columnNames = (array)$columnNames;
        }
        foreach ($columnNames as $columnName) {
            $this->orderByClause->put([$columnName => $order]);
        }
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
        $tableReference .= "{$this->table}";
        if ($this->joinClauses->length() > 0) $tableReference .= " " . $this->joinClauses->stream()->join(' ');
        // --------- GROUP BY
        if ($this->groupByColumns->length() > 0) $groupByClause = " GROUP BY " . $this->groupByColumns->stream()->join(', ');
        // --------- WHERE / HAVING
        if ($this->whereClause->length() > 0) $whereClause = !empty($groupByClause) ? " HAVING " : " WHERE ";
        $whereClause .= $this->whereClause->join();
        $this->values->putAll($this->whereClause->getValues());
        // --------- ORDER BY


        return
            "SELECT {$selectExpression} FROM {$tableReference}{$groupByClause}{$whereClause}{$orderByClause}{$this->limitClause}";
    }
}