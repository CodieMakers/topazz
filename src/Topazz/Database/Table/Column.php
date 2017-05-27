<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


use Topazz\Data\RandomStringGenerator;

class Column {

    protected $name;
    protected $dataType;
    protected $primary = false;
    protected $autoIncrement = true;
    protected $nullable = true;
    protected $unsigned = false;
    protected $unique = false;
    protected $indexed = false;
    protected $foreignKey = false;
    protected $referenceTable;
    protected $referenceColumn;
    protected $defaultValue;

    public function __construct(string $name) {
        $this->name = $name;
    }

    /* -------------------------------------------
     *                  SETTERS
     * ------------------------------------------- */

    public function type(string $dataType) {
        $this->dataType = $dataType;
        return $this;
    }

    public function primary($autoIncrement = true) {
        $this->primary = true;
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    public function foreignKey(string $table, string $column) {
        $this->foreignKey = true;
        $this->referenceTable = $table;
        $this->referenceColumn = $column;
        return $this;
    }

    public function unique() {
        $this->unique = true;
        return $this;
    }

    public function unsigned() {
        $this->unsigned = true;
        return $this;
    }

    public function notNull() {
        $this->nullable = false;
        return $this;
    }

    public function default($value) {
        $this->defaultValue = $value;
        return $this;
    }

    /* -------------------------------------------
     *                  GETTERS
     * ------------------------------------------- */

    public function getName(): string {
        return $this->name;
    }

    public function required(): bool {
        return !$this->nullable && is_null($this->defaultValue);
    }

    /* -------------------------------------------
     *                  MAGIC
     * ------------------------------------------- */

    public function __toString(): string {
        $sql = "`{$this->name}` {$this->dataType}";

        if ($this->dataType === "SERIAL") {
            return $sql;
        }

        if ($this->unsigned &&
            preg_match('/(INTEGER|INT|BIGINT|SMALLINT|TINYINT)(?:\(\d+\))?/', $this->dataType)) {
            $sql .= " UNSIGNED";
        }

        if (!$this->nullable) {
            $sql .= " NOT NULL";
        }

        if (!is_null($this->defaultValue)) {
            $default = $this->defaultValue;
            if (preg_match('/(VARCHAR\(\d+\)|TEXT|BOOLEAN)/', $this->dataType)) {
                $default = (string)$default;
            }
            $sql .= " DEFAULT {$default}";
        }

        if ($this->unique) {
            $sql .= " UNIQUE";
        }

        if ($this->primary) {
            $sql .= " PRIMARY KEY" . ($this->autoIncrement ? " AUTO_INCREMENT" : "");
        }

        if ($this->foreignKey) {
            $sql .= " REFERENCES {$this->referenceTable} ({$this->referenceColumn})";
        }

        return $sql;
    }

    public static function create(string $name) {
        return new Column($name);
    }

    public static function id() {
        return (new Column("id"))->type("SERIAL")->primary();
    }
}