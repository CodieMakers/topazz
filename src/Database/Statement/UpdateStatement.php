<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collections\Lists\ArrayList;

class UpdateStatement extends Statement {

    protected $table;
    protected $whereClause;
    protected $setters = [];

    public function __construct(string $table) {
        parent::__construct();
        $this->table = $table;
        $this->setters = new ArrayList();
        $this->whereClause = new WhereClause();
    }

    public function set(string $column, $value): UpdateStatement {
        $this->setters->put($column);
        $this->values->put($value);
        return $this;
    }

    public function where(string $columnName, $value): UpdateStatement {
        $this->whereClause->where($columnName, $value);
        return $this;
    }

    public function orWhere(string $columnName, $value): UpdateStatement {
        $this->whereClause->where($columnName, $value, false, "OR");
        return $this;
    }

    public function whereNot(string $columnName, $value): UpdateStatement {
        $this->whereClause->where($columnName, $value, true);
        return $this;
    }

    public function orWhereNot(string $columnName, $value): UpdateStatement {
        $this->whereClause->where($columnName, $value, true, "OR");
        return $this;
    }

    public function whereExists(StatementInterface $statement): UpdateStatement {
        $this->whereClause->whereExists($statement);
        return $this;
    }

    public function whereNotExists(StatementInterface $statement): UpdateStatement {
        $this->whereClause->whereExists($statement, true);
        return $this;
    }

    public function getQueryString(): string {
        $where = "";
        $set = $this->setters->stream()->map(function ($setter) {
            return "$setter = ?";
        })->toString(', ');

        if ($this->whereClause->length() > 0) $where = " WHERE " . $this->whereClause->join();
        $this->values->putAll($this->whereClause->getValues());

        return "UPDATE {$this->table} SET {$set}{$where}";
    }
}