<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Database;

class DropTableStatement extends Statement {

    protected $table;

    public function __construct(Database $dbh, string $table) {
        parent::__construct($dbh);
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function __toString(): string {
        return "DROP TABLE IF EXISTS `{$this->table}`";
    }
}