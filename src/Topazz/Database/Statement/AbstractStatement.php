<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class AbstractStatement extends Statement {

    protected $queryString;

    public function __construct(string $query) {
        parent::__construct();
        $this->queryString = $query;
    }

    public function values($values) {
        $this->values->putAll($values);
        return $this;
    }

    public function getQueryString(): string {
        return $this->queryString;
    }
}