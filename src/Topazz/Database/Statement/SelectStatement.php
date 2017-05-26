<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Clause\GroupClause;
use Topazz\Database\Database;

class SelectStatement extends BasicStatement {

    protected $what;
    protected $from = [];
    protected $distinct = false;
    protected $groupClause;
    protected $joinClause;
    protected $limitClause;

    public function __construct(Database $connection, $what = ["*"]) {
        parent::__construct($connection);
        $this->what = $what;
        $this->groupClause = new GroupClause();
    }

    public function from(string $table) {
        $this->from[] = $table;
        return $this;
    }

    public function group($key) {
        $this->whereClause->having();
        $this->groupClause->groupBy($key);
        return $this;
    }

    public function __toString(): string {
        $sql =  "SELECT " . implode(', ', $this->what);
        $sql .= " FROM " . implode(', ', $this->from);
        $sql .= (string)$this->whereClause;
        return $sql;
    }
}