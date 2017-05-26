<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;



use Topazz\Database\Database;

class CreateUserStatement extends Statement {

    protected $username;
    protected $password;

    public function __construct(Database $dbh, string $username, string $password) {
        parent::__construct($dbh);
        $this->username = $username;
        $this->password = $password;
    }

    public function __toString(): string {
        /** @lang MySQL */
        return "CREATE USER IF NOT EXISTS `{$this->username}` IDENTIFIED BY '{$this->password}'";
    }
}