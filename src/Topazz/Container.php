<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Slim\Container as SlimContainer;
use Topazz\Module\ModuleManager;

class Container extends SlimContainer {

    private $modules;

    public function __construct(array $values = []) {
        parent::__construct($values);
    }

    public function add($callable) {
        $this->factory($callable);
        return $this;
    }

    public function getModuleManager() {
        if (is_null($this->modules)) {
            $this->modules = new ModuleManager(Application::getInstance()->getContainer());
        }
        return $this->modules;
    }
}