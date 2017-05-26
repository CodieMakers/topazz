<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use PDO;
use Topazz\Application;
use Topazz\Data\Collection;

abstract class Entity {

    public $id;
    /** @var Connector $db */
    protected $db;

    public function __construct() {
        $this->db = self::db();
    }

    /**
     * @return Connector
     */
    private static function db() {
        return Application::getInstance()->getContainer()->get("db");
    }

    public static function all() {
        return self::db()->connect()->select()->from(static::getTable()->getName())->execute()->all();
    }

    public static function findBy($key, $operator, $value) {
        $select = self::db()->select()
            ->from(static::getTable()->getName())
            ->where($key, $operator, $value);
        $result = $select->execute();
        return new Collection($result->fetchAll());
    }

    public static function findById(int $id) {
        return self::findBy("id", "=", $id)->first();
    }

    abstract public static function getTable(): Table;

    public function entityName(): string {
        $entityTable = static::getTable()->getName();
        return substr($entityTable, 0, strlen($entityTable) - 2);
    }

    abstract public function save();
}