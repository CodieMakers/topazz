<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Clause;


class WhereClause extends Clause {

    protected $isHaving = false;

    public function where($clause, $glue = "AND") {
        if (!empty($this->clause)) {
            $this->clause .= " {$glue} ";
        }
        $this->clause .= $clause;
    }

    public function having() {
        $this->isHaving = true;
    }

    public function __toString(): string {
        return " " . ($this->isHaving ? "HAVING" : "WHERE") . " " . $this->clause;
    }
}