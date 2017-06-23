<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collections\Maps\HashMap;

class OrderByClause {

    protected $columns;

    public function __construct() {
        $this->columns = new HashMap();
    }

    public function add(string $columnName, $order = "ASC") {

    }

    public function __toString(): string {
        $orderBy = "";
        if ($this->columns->keys()->length() > 0) {
            $orderBy .= "ORDER BY ";
        }
        $this->columns->each(function ($item) use ($orderBy) {

        });
        return $orderBy;
    }
}