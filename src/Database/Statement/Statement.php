<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Table\Table;

abstract class Statement implements StatementInterface {

    protected $table;
    protected $values;
    protected $columns;

    public function __construct(Table $table) {
        $this->table = $table;
        $this->values = new ArrayList();
        $this->columns = $table->getColumns();
    }

    public function getValues(): array {
        return $this->values->toArray();
    }
}