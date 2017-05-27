<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Psr\Container\ContainerInterface;
use Topazz\Application;
use Topazz\Data\Collection;
use Topazz\Database\Connector;
use Topazz\Database\Database;
use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;
use Topazz\Entity\EntityInterface;

abstract class Module implements EntityInterface {

    public $id;
    public $name;
    public $module_class;
    public $activated = false;
    /** @var ContainerInterface $container */
    protected $container;
    /** @var Application $application */
    protected $application;

    public function __construct() {
        $this->application = Application::getInstance();
        $this->container = $this->application->getContainer();
        if (is_null($this->module_class)) {
            $this->module_class = static::class;
        }
    }

    public function isActivated() {
        return $this->activated;
    }

    abstract public function isEnabled();

    abstract public function setup();

    public static function all(): Collection {
        return Connector::connect()->setEntity(self::class)
            ->setStatement(Database::select()->from("modules"))->execute()->all();
    }

    public static function find(string $key, $value): Collection {
        $select = Database::select()->from('modules');
        return Connector::connect()->setEntity(self::class)->setStatement(
            is_string($value) ? $select->whereLike($key, $value) : $select->whereIs($key, $value)
        )->execute()->all();
    }

    public static function getTable(): Table {
        return Table::create("modules")->addColumns(
            Column::id(),
            Column::create("name")->type("VARCHAR(50)")->notNull(),
            Column::create("activated")->type("BOOLEAN")->default(false),
            Column::create("module_class")->type("VARCHAR(255)")->notNull()
        );
    }
}