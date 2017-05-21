<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Database\Table;

class ModulesTable extends Table {

    public function __construct() {
        parent::__construct("modules",
            [
                "id" => ["type" => "SERIAL"],
                "name" => ["type" => "VARCHAR(255)", "unique" => true],
                "module_class" => ["type" => "VARCHAR(255)", "null" => false],
                "activated" => ["type" => "BOOLEAN", "default" => false, "null" => false]
            ]
        );
    }
}