<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


use Topazz\Database\Statement\DeleteStatement;
use Topazz\Database\Statement\InsertStatement;
use Topazz\Database\Statement\SelectStatement;
use Topazz\Database\Statement\UpdateStatement;

class Table {

    protected $name;
    protected $columns;
    protected $constraints;

    public function __construct(string $name) {
        $this->name = $name;
        $this->columns = new ColumnCollection();
    }

    public function columns(Column... $columns) {
        $this->columns->putAll($columns);
        return $this;
    }

    public function name(string $name) {
        $this->name = $name;
        return $this;
    }

    public function getColumns() {
        return $this->columns;
    }

    public function getColumnNames(): array {
        return $this->columns->map(function (Column $column) {
            return $column->getName();
        })->toArray();
    }

    public function getName(): string {
        return $this->name;
    }

    public function getColumn(string $columnName) {
        return $this->columns->get($columnName)->orNull();
    }

    public function getConstraints() {
        if (is_null($this->constraints)) {
            $this->constraints = [
                "primaryKeys" => $this->columns->filter([ColumnFilter::class, "primary"]),
                "foreignKeys" => $this->columns->filter([ColumnFilter::class, "foreign"])
            ];
        }
        return $this->constraints;
    }

    public function getSelectStatement(): SelectStatement {
        return new SelectStatement($this);
    }

    public function getUpdateStatement(): UpdateStatement {
        return new UpdateStatement($this);
    }

    public function getInsertStatement(): InsertStatement {
        return new InsertStatement($this);
    }

    public function getDeleteStatement(): DeleteStatement {
        return new DeleteStatement($this);
    }

    public static function create(string $table) {
        return new Table($table);
    }

    public static function duplicate(Table $table) {
        return clone $table;
    }
}