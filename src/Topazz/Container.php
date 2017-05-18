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
        $this->modules = new ModuleManager($this);
    }

    public function add($callable) {
        $this->factory($callable);
        return $this;
    }

    public function getModules() {
        return $this->modules;
    }
}