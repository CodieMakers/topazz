<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class TableBuilder {

    const INTEGER = "INTEGER";
    const SMALLINT = "SMALLINT";
    const BIGINT = "BIGINT";
    const TINYINT = "TINYINT";

    /** @var [mixed[]] $columns */
    private $columns = [];
    private $name;
    private $currentColumn;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function serial(string $column): TableBuilder {
        return $this->addColumn($column, [
            "type" => "SERIAL",
            "primary" => true,
            "unique" => true,
            "null" => false,
            "autoincrement" => true
        ]);
    }

    public function varchar(string $column, int $charCount = 255): TableBuilder {
        return $this->addColumn($column, [
            "type" => "VARCHAR(" . $charCount . ")"
        ]);
    }

    public function integer(string $column, string $sizeType = "INTEGER", int $size = 0): TableBuilder {
        return $this->addColumn($column, [
            "type" => $sizeType . ($size > 0 ? "(" . $size . ")" : "")
        ]);
    }

    /**
     * @param string       $column  Column name
     * @param array|string $options Options or just data type
     *
     * @return TableBuilder
     */
    public function addColumn(string $column, $options): TableBuilder {
        if (is_string($options)) {
            $options = ["type" => $options];
        }
        $this->currentColumn = $column;
        return $this->setColumn($column, $options);
    }

    public function primary(): TableBuilder {
        return $this->alterCurrentColumn(["primary" => true]);
    }

    public function unique(): TableBuilder {
        return $this->alterCurrentColumn(["unique" => true]);
    }

    public function null(bool $not = false): TableBuilder {
        return $this->alterCurrentColumn(["null" => !$not]);
    }

    public function notNull(): TableBuilder {
        return $this->null(true);
    }

    public function unsigned(): TableBuilder {
        return $this->alterCurrentColumn(["unsigned" => true]);
    }

    public function default($value): TableBuilder {
        return $this->alterCurrentColumn(["default" => $value]);
    }

    public function foreignKey(string $table, string $reference): TableBuilder {
        return $this->alterCurrentColumn([
            "foreign" => $table . ":" . $reference
        ]);
    }

    public function alterCurrentColumn(array $options): TableBuilder {
        return $this->setColumn($this->currentColumn, $options);
    }

    public function setColumn(string $column, array $options): TableBuilder {
        if (array_key_exists($column, $this->columns)) {
            $this->columns[$column] = array_merge($this->columns[$column], $options);
        } else {
            $this->columns[$column] = $options;
        }
        return $this;
    }

    public function create(): Table {
        return new Table($this->name, $this->columns);
    }
}