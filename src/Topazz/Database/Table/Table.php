<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


use Topazz\Data\Collection;

class Table {

    protected $name;
    protected $columns;
    protected $charset = "utf8";
    protected $collation = "utf_general_ci";
    protected $engine = "InnoDB";

    public function __construct(string $name) {
        $this->name = $name;
        $this->columns = new Collection();
    }

    public function addColumns(Column... $columns) {
        $this->columns->putAll($columns);
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

    public static function create(string $table) {
        return new Table($table);
    }
}