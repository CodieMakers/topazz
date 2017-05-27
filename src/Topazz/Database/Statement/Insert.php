<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class Insert extends Statement {

    protected $what;
    protected $into;

    public function __construct($what) {
        parent::__construct();
        $this->what = $what;
    }

    public function into(string $table) {
        $this->into = $table;
        return $this;
    }

    public function values($values) {
        $this->values->putAll($values);
        return $this;
    }

    public function getQueryString(): string {
        $sql = "INSERT INTO {$this->into} ";
        if (!empty($this->what)) {
            $sql .= "(" . join(", ", $this->what) . ") ";
        }
        $sql .= "VALUES (" . join(",", array_fill(0, $this->values->length() - 1, "?")) . ")";
        return $sql;
    }
}