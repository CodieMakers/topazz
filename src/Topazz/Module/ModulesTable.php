<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Database\Table\Table;

class ModulesTable extends Table {

    public function __construct() {
        parent::__construct("modules");
    }
}