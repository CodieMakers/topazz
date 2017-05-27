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
use Topazz\Data\ObjectCollection;
use Topazz\Environment;
use Topazz\Installer\InstallerModule;

class ModuleManager {

    private static $instance;
    /** @var ContainerInterface $container */
    private $container;
    /** @var Module[] $registeredModules */
    private $registeredModules = [];

    protected function __construct() {
        $this->container = Application::getInstance()->getContainer();
        $this->registeredModules = new ObjectCollection();
        $this->registeredModules->put(new InstallerModule());
        if (Environment::get("ENV") !== "installation") {
            $this->registeredModules->putAll(Module::all());
        }
    }

    public function findModule(string $moduleName) {
        return $this->registeredModules->find(["name" => $moduleName])->orNull();
    }

    public function run() {
        /** @var Module $module */
        foreach ($this->registeredModules as $module) {
            if ($module->isActivated() && $module->isEnabled()) {
                $module->setup();
            }
        }
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new ModuleManager();
        }
        return self::$instance;
    }
}