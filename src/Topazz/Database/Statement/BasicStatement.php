<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Clause\WhereClause;
use Topazz\Database\Database;

abstract class BasicStatement extends Statement {

    protected $whereClause;

    public function __construct(Database $connection) {
        parent::__construct($connection);
        $this->whereClause = new WhereClause();
    }

    public function where($key, $comparison, $value) {
        $this->values[] = $value;
        $this->whereClause->where($key, $comparison);
        return $this;
    }

    public function orWhere($key, $comparison, $value) {
        $this->values[] = $value;
        $this->whereClause->where($key, $comparison, "OR");
        return $this;
    }
}