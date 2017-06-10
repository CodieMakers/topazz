<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Table\Table;

class InsertStatement extends Statement {

    protected $what;
    protected $into;
    protected $valuesInserted = 0;

    public function __construct(Table $table) {
        parent::__construct($table);
        $this->what = $table->getColumns();
    }

    public function values(... $values) {
        $this->values->putAll($values);
        $this->valuesInserted++;
        return $this;
    }

    public function getQueryString(): string {
        $sql = "INSERT INTO {$this->table->getName()} ";
        if (!empty($this->what)) {
            $sql .= "(" . $this->what->keys()->join(', ') . ") ";
        }
        $sql .= "VALUES ";
        $values = [];
        for ($i = 0; $i <= $this->valuesInserted; $i++) {
            $values[] = "(" . join(",", array_fill(0, $this->what->keys()->length() - 1, "?")) . ")";
        }
        return $sql . join(', ', $values);
    }
}