<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Clause\GroupClause;
use Topazz\Database\Clause\JoinClause;

class Select extends BasicStatement {

    protected $what;
    protected $distinct = false;
    protected $from;
    protected $joinClause;
    protected $groupClause;
    protected $limitClause;

    public function __construct($what = ["*"]) {
        parent::__construct();
        $this->what = $what;
        $this->groupClause = new GroupClause();
        $this->joinClause = new JoinClause();
    }

    public function distinct() {
        $this->distinct = true;
        return $this;
    }

    public function from(string $table) {
        $this->from = $table;
        return $this;
    }

    public function groupBy(string... $columns) {
        $this->whereClause->having();
        foreach ($columns as $column) {
            $this->groupClause->groupBy($column);
        }
        return $this;
    }

    public function getQueryString(): string {
        $sql =
            "SELECT " . ($this->distinct ? "DISTINCT " : "") . join(", ", $this->what) .
            " FROM {$this->from}";

        // TODO: complete query

        return $sql;
    }
}