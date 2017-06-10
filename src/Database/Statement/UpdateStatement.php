<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Table\Table;

class UpdateStatement extends StatementWithWhereClause {

    protected $what;
    protected $setters = [];

    public function __construct(Table $table) {
        parent::__construct($table);
    }

    public function set(string $column, $value) {
        $this->setters[] = $column;
        $this->values->put($value);
        return $this;
    }

    public function getQueryString(): string {
        $sql = "UPDATE {$this->what} SET ";
        $setters = new ArrayList($this->setters);
        $sql .= $setters->map(function ($setter) {
            return "$setter = ?";
        })->join(', ');
        return $sql . $this->whereExpressions;
    }
}