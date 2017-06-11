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
        foreach ($columns as $column) {
            $this->columns->set($column->getName(), $column);
        }
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
        return $this->columns->keys()->toArray();
    }

    public function getName(): string {
        return $this->name;
    }

    public function getColumn(string $columnName) {
        return $this->columns->get($columnName)->orNull();
    }

    public function getSelect(string... $columns): SelectStatement {
        return (new SelectStatement(...$columns))->from($this->getName());
    }

    public function getUpdate(): UpdateStatement {
        return new UpdateStatement($this->getName());
    }

    public function getInsert(string... $columns): InsertStatement {
        return (new InsertStatement(...$columns))->into($this->getName());
    }

    public function getDelete(): DeleteStatement {
        return new DeleteStatement($this->getName());
    }

    public function __clone() {
        $clone = clone $this;
        $clone->columns = clone $this->columns;
        return $clone;
    }

    public static function create(string $table) {
        return new Table($table);
    }

    public static function duplicate(Table $table) {
        return clone $table;
    }
}