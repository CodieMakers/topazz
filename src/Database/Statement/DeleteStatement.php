<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Database\Clause\WhereClause;
use Topazz\Database\Table\Table;

class DeleteStatement extends StatementWithWhereClause {

    public function getQueryString(): string {
        return "DELETE FROM {$this->table->getName()}" . $this->whereExpressions;
    }
}