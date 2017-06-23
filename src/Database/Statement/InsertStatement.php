<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class InsertStatement extends Statement {

    protected $what;
    protected $table;
    protected $valuesInserted = 0;

    public function __construct(string... $columnNames) {
        parent::__construct();
        $this->what = $columnNames;
    }

    public function into(string $table): InsertStatement {
        $this->table = $table;
        return $this;
    }

    public function values(... $values): InsertStatement {
        $this->values->putAll($values);
        $this->valuesInserted++;
        return $this;
    }

    public function getQueryString(): string {
        $sql = "INSERT INTO {$this->table} ";
        if (!empty($this->what)) {
            $sql .= "(" . join(', ', $this->what) . ") ";
        }
        $sql .= "VALUES ";
        $values = [];
        for ($i = 1; $i <= $this->valuesInserted; $i++) {
            $values[] = "(" . join(",", array_fill(0, count($this->what), "?")) . ")";
        }
        return $sql . join(', ', $values);
    }
}