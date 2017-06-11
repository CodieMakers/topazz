<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class DeleteStatement extends Statement {

    protected $table;
    protected $whereClause;

    public function __construct(string $table) {
        parent::__construct();
        $this->table = $table;
        $this->whereClause = new WhereClause($this->values);
    }

    public function where(string $columnName, $value): DeleteStatement {
        $this->whereClause->where($columnName, $value);
        return $this;
    }

    public function orWhere(string $columnName, $value): DeleteStatement {
        $this->whereClause->where($columnName, $value, false, "OR");
        return $this;
    }

    public function whereNot(string $columnName, $value): DeleteStatement {
        $this->whereClause->where($columnName, $value, true);
        return $this;
    }

    public function orWhereNot(string $columnName, $value): DeleteStatement {
        $this->whereClause->where($columnName, $value, true, "OR");
        return $this;
    }

    public function whereExists(StatementInterface $statement): DeleteStatement {
        $this->whereClause->whereExists($statement);
        return $this;
    }

    public function whereNotExists(StatementInterface $statement): DeleteStatement {
        $this->whereClause->whereExists($statement, true);
        return $this;
    }

    public function getQueryString(): string {
        $where = "";
        if ($this->whereClause->length() > 0) $where = " WHERE " . $this->whereClause->join();
        return "DELETE FROM {$this->table}{$where}";
    }
}