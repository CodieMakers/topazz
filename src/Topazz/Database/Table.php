<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class Table {

    protected $columns;
    protected $name;

    public function __construct(string $name, array $columns) {
        $this->name = $name;
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns(): array {
        return $this->columns;
    }

    public function getColumnNames(): array {
        return array_keys($this->columns);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
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
        if (isset($options["default"])) {
            $column .= " DEFAULT " . $options["default"];
        }
        return $column;
    }

    public function getColumnsForTableCreation() {
        $columns = [];
        foreach ($this->columns as $column => $options) {
            $columns[$column] = $this->parseColumnOptions($options);
        }
        return $columns;
    }
}