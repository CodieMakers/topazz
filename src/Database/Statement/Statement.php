<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Application;
use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Database\Connector;
use Topazz\Database\Database;

abstract class Statement implements StatementInterface {

    protected $values;

    public function __construct() {
        $this->values = new ArrayList();
    }

    public function getValues(): array {
        return $this->values->toArray();
    }

    public function prepare(string $entity = \stdClass::class): Database {
        /** @var Connector $connector */
        $connector = Application::getInstance()->getApp()->getContainer()->get('connector');
        return $connector->connect()->setEntity($entity)->setStatement($this);
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