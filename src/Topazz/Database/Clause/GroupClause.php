<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Clause;


class GroupClause extends Clause {

    protected $keys = [];

    public function groupBy($key) {
        $this->keys[] = $key;
    }

    public function __toString(): string {
        return " GROUP BY " . join(', ', $this->keys);
    }
}