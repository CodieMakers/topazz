<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Proxy;


use Topazz\Database\Query;

class SingleToManyProxy extends Proxy {

    public function __construct(string $table, string $column, $value, $entityClass) {
        parent::__construct(
            Query::create(
                "SELECT * FROM $table WHERE $column = ?"
            )->addAttribute($value),
            $entityClass
        );
    }
}