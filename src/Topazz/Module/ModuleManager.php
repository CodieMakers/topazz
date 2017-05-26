<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Container;
use Topazz\Installer\InstallerModule;

class ModuleManager {

    /** @var Container $container */
    private $container;
    /** @var Module[] $registeredModules */
    private $registeredModules = [];

    public function __construct(Container $container) {
        $this->container = $container;
        $this->registeredModules[] = new InstallerModule();
        if (getenv("ENV") !== "installation") {
            $this->registeredModules = array_merge($this->registeredModules, Module::all()->toArray());
        }
    }

    public function findModule(string $moduleName) {
        foreach ($this->registeredModules as $module) {
            if ($module->name === $moduleName) {
                return $module;
            }
        }
        return null;
    }

    public function run() {
        /** @var Module $module */
        foreach ($this->registeredModules as $module) {
            if ($module->isActivated() && $module->isEnabled()) {
                $module->setup();
            }
        }
    }
}