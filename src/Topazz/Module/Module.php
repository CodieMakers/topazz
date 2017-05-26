<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Slim\App;
use Topazz\Application;
use Topazz\Container;
use Topazz\Database\Entity;
use Topazz\Database\Table;
use Topazz\View\Twig;

abstract class Module extends Entity {

    public $name;
    public $module_class;
    public $activated = false;
    /** @var Container $container */
    protected $container;
    /** @var App $router */
    protected $router;

    public function __construct() {
        parent::__construct();
        $this->container = Application::getInstance()->getContainer();
        $this->router = Application::getInstance();
    }

    public function isActivated() {
        return $this->activated;
    }

    public static function getTable(): Table {
        return new ModulesTable();
    }

    public function save() {
        // TODO: Implement save() method.
    }


    /**
     * @return boolean Returns TRUE if this module is enabled, otherwise FALSE.
     */
    abstract public function isEnabled();

    /**
     * @return void
     */
    abstract public function setup();
}