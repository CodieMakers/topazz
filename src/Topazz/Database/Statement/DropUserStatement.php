<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Database;

class DropUserStatement extends Statement {

    protected $username;

    public function __construct(Database $connection, string $username) {
        parent::__construct($connection);
        $this->username = $username;
    }

    public function __toString(): string {
        return "DROP USER IF EXISTS '{$this->username}'@'{" . getenv("DB_HOST") . "}'";
    }
}