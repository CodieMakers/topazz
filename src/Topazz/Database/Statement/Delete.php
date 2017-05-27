<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class Delete extends BasicStatement {

    protected $from;

    public function __construct($from) {
        parent::__construct();
        $this->from = $from;
    }

    public function getQueryString(): string {
        return "DELETE FROM {$this->from}" . (string)$this->whereClause;
    }
}