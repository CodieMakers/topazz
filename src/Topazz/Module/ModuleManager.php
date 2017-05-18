<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Container;

class ModuleManager {

    private $container;
    /**
     * @var Module[] $registeredModules
     */
    private $registeredModules = [];
    /**
     * @var Module[] $enabledModules
     */
    private $enabledModules = [];

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * @param string $module
     *
     * @return ModuleManager
     */
    public function add($module) {
        $this->registeredModules[] = $module;
        return $this;
    }

    public function run() {
        foreach ($this->registeredModules as $module) {
            /** @var Module $module */
            $module = new $module($this->container);
            if ($module->isEnabled()) {
                $this->enabledModules[] = $module;
                $module->setup();
            }
        }
    }
}