<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\RandomStringGenerator;
use Topazz\Database\Database;

class CreateTableStatement extends Statement {

    protected $table;
    protected $columns = [];
    protected $engine = "InnoDB";
    protected $charset = "utf8";
    protected $collate = "utf8_general_ci";

    public function __construct(Database $dbh, string $table) {
        parent::__construct($dbh);
        $this->table = $table;
    }

    public function columns(array $columns) {
        $this->columns = $columns;
    }

    public function engine(string $engine) {
        $this->engine = $engine;
    }

    public function charset(string $charset) {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function __toString(): string {
        return "CREATE TABLE IF NOT EXISTS {$this->table} ({$this->getColumnsDefinition()}) DEFAULT CHARACTER SET {$this->charset} COLLATE {$this->collate} ENGINE {$this->engine}";
    }

    protected function getColumnsDefinition() {
        $sql = [];
        $constraints = [];
        RandomStringGenerator::withoutSpecials();
        foreach ($this->columns as $column => $options) {
            if (!is_string($column) && isset($options["column"])) {
                $column = $options["column"];
            }
            $columnSql = "`{$column}` ";
            if ($options["type"] === "SERIAL" && !isset($options["foreign_key"])) {
                $columnSql .= "SERIAL";
                $sql[] = $columnSql;
                continue;
            } elseif ($options["type"] === "SERIAL" && isset($options["foreign_key"])) {
                $columnSql .= "BIGINT UNSIGNED NOT NULL";
                $constraints[] = "CONSTRAINT fk_" . RandomStringGenerator::generate(15) .
                    " FOREIGN KEY ({$column}) REFERENCES `{$options["foreign_key"]["table"]}` ({$options["foreign_key"]["column"]})";
                $sql[] = $columnSql;
                continue;
            } else {
                $columnSql .= $options["type"];
            }
            if (isset($options["null"])) {
                $columnSql .= $options["null"] === false ? " NOT NULL" : " NULL";
            }
            if (isset($options["default"])) {
                $columnSql .= " DEFAULT ";
                if (preg_match('/(VARCHAR\(\d+\)|TEXT)/', $options["type"])) {
                    $columnSql .= "'{$options["default"]}'";
                } elseif ($options["type"] === "BOOLEAN") {
                    $columnSql .= $options["default"] === true ? "TRUE" : "FALSE";
                } else {
                    $columnSql .= $options["default"];
                }
            }
            if (isset($options["unique"]) && $options["unique"] === true) {
                $constraints[] = "CONSTRAINT unique_" . RandomStringGenerator::generate(15) .
                    " UNIQUE ({$column})";
            }
            $sql[] = $columnSql;
        }
        $sql[] = join(',' . PHP_EOL, $constraints);
        return join(',' . PHP_EOL, $sql);
    }
}