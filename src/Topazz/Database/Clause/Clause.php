<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Clause;


abstract class Clause {

    protected $clause = "";

    public function __toString(): string {
        return $this->clause;
    }
}