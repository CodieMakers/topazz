<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Application;
use Topazz\Container;
use Topazz\Data\Collections\ListInterface;
use Topazz\Database\Statement\Statement;

abstract class Entity implements EntityInterface {

    protected static $table;
    /** @var Container $container */
    protected $container;

    public $id;

    public function __construct() {
        $this->container = Application::getInstance()->getApp()->getContainer();
    }

    public function save() {
        if (isset($this->id)) {
            $this->update();
        } else {
            $this->create();
        }
    }

    abstract protected function create();
    abstract protected function update();

    public static function all(): ListInterface {
        return Statement::select()->from(static::$table)
            ->prepare(static::class)->execute()->all();
    }

    public static function find(string $key, $value): ListInterface {
        return Statement::select()->from(static::$table)->where($key, $value)
            ->prepare(static::class)->execute()->all();
    }

    public static function findById(int $id) {
        return static::find("id", $id)->first()->orThrow(new EntityNotFoundException());
    }

    public static function remove(int $id): bool {
        $statement = Statement::delete(static::$table)
            ->where('id', $id)
            ->prepare()->inTransaction()->execute();
        if ($statement->count() === 1) {
            $statement->commit();
            return true;
        }
        return false;
    }

    function __set($name, $value) {
        $this->$name = $value;
    }

    function __get($name) {
        if (array_key_exists($name, get_object_vars($this))) {
            return $this->$name;
        }
        return null;
    }
}