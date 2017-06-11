<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Database\Connector;
use Topazz\Database\Result;
use Topazz\Database\Table\Table;

abstract class Statement implements StatementInterface {

    protected $values;
    protected $entity = \stdClass::class;

    public function __construct() {
        $this->values = new ArrayList();
    }

    public function getValues(): array {
        return $this->values->toArray();
    }

    public function setEntity(string $entity) {
        $this->entity = $entity;
        return $this;
    }

    public function execute(): Result {
        return Connector::connect()->setEntity($this->entity)->setStatement($this)->execute();
    }

    public static function select(string... $columns) {
        return new SelectStatement(...$columns);
    }

    public static function insert(string... $columns) {
        return new InsertStatement(...$columns);
    }

    public static function update(string $table) {
        return new UpdateStatement($table);
    }

    public static function delete(string $table) {
        return new DeleteStatement($table);
    }
}