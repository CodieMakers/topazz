<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class Entity {

    protected $table;

    public function __construct(Table $table) {
        $this->table = $table;
    }
}