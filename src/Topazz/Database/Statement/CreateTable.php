<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection;
use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;

class CreateTable extends Statement {

    protected $table;
    protected $engine = "InnoDB";
    protected $charset = "utf8";
    protected $collate = "utf8_general_ci";

    public function __construct(string $table) {
        parent::__construct();
        $this->table = $table;
    }

    /**
     * @param Column...|array $columns
     *
     * @return $this
     */
    public function columns($columns) {
        $this->values->putAll($columns);
        return $this;
    }

    public function engine(string $engine) {
        $this->engine = $engine;
        return $this;
    }

    public function charset(string $charset) {
        $this->charset = $charset;
        return $this;
    }

    public function collation(string $collate) {
        $this->collate = $collate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQueryString(): string {
        $columns = $this->values->map(function (Column $column) {
            return (string)$column;
        })->toArray();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (";
        $sql .= join(',' . PHP_EOL, $columns);
        $sql .= ") DEFAULT CHARACTER SET {$this->charset} COLLATE {$this->collate} ENGINE {$this->engine}";

        $this->values->clear();
        return $sql;
    }

    public static function fromTable(Table $table) {
        $statement = new CreateTable($table->getName());
        $statement->columns($table->getColumns()->toArray());
        return $statement;
    }
}