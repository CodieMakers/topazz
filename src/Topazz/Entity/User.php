<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Entity;
use Topazz\Database\Table;

class User extends Entity {

    public function __construct() {
        $table = new Table("users");
        parent::__construct(
            $table->serial("id")
            ->varchar("username", 45)
            ->varchar("email", 50)
            ->varchar("first_name")
            ->varchar("last_name")
            ->integer("role", Table::SMALLINT)->unsigned("role")
        );
    }
}