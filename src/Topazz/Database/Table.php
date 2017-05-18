<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class Table {

    const INTEGER = "INTEGER";
    const SMALLINT = "SMALLINT";
    const BIGINT = "BIGINT";
    const TINYINT = "TINYINT";

    /** @var [array] $columns */
    private $columns = [];
    private $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function serial(string $column) {
        return $this->addColumn($column, [
            "type" => "SERIAL",
            "primary" => true,
            "unique" => true,
            "null" => false,
            "autoincrement" => true
        ]);
    }

    public function varchar($columnName, int $charCount = 255) {
        return $this->addColumn($columnName, [
            "type" => "VARCHAR(" . $charCount . ")"
        ]);
    }

    public function integer(string $columnName, string $sizeType = "INTEGER", int $size = 0) {
        return $this->addColumn($columnName, [
            "type" => $sizeType . ($size > 0 ? "(" . $size . ")" : "")
        ]);
    }

    /**
     * @param string       $column
     * @param array|string $options
     *
     * @return Table
     */
    public function addColumn(string $column, $options): Table {
        if (is_string($options)) {
            $options = ["type" => $options];
        }
        $this->columns[$column] = $options;
        return $this;
    }

    /**
     * @param string $column
     *
     * @return Table
     */
    public function removeColumn(string $column): Table {
        unset($this->columns[$column]);
        return $this;
    }

    public function primary(string $column): Table {
        return $this->alterColumn($column, ["primary" => true]);
    }

    public function unique(string $column) {
        return $this->alterColumn($column, ["unique" => true]);
    }

    public function null(string $column, bool $not = false) {
        return $this->alterColumn($column, ["null" => !$not]);
    }

    public function unsigned(string $column) {
        return $this->alterColumn($column, ["unsigned" => true]);
    }

    public function foreignKey(string $column, string $table, string $reference) {
        return $this->alterColumn($column, [
            "foreign" => $table . ":" . $reference
        ]);
    }

    public function alterColumn(string $column, array $options): Table {
        if (array_key_exists($column, $this->columns)) {
            $this->columns[$column] = array_merge($this->columns[$column], $options);
        }
        return $this;
    }

    public function getColumn(string $column) {
        return $this->columns[$column];
    }

    /**
     * @return array
     */
    public function getColumns(): array {
        return $this->columns;
    }

    private function parseColumnOptions($options) {
        $column = $options["type"];
        if ($options["type"] == "SERIAL") { //don't continue
            return $column;
        }
        if (isset($options["primary"]) && $options["primary"]) {
            $column .= " PRIMARY KEY";
        }
        if (isset($options["unique"]) && $options["unique"]) {
            $column .= " UNIQUE";
        }
        return $column;
    }

    public function parseColumns() {
        $columns = [];
        foreach ($this->columns as $column => $options) {
            $columns[$column] = $this->parseColumnOptions($options);
        }
        return $columns;
    }
}