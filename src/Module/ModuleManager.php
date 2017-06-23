<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Config\Config;
use Topazz\Config\Configuration;
use Topazz\Container;
use Topazz\Data\Collections\ListInterface;
use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Event\EventEmitter;

class ModuleManager {

    /** @var Container $container */
    protected $container;
    /** @var Configuration $config */
    protected $config;
    /** @var EventEmitter $events */
    protected $events;
    /** @var ModuleInstaller $installer */
    protected $installer;

    protected $modules;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->config = $container->get('config');
        $this->events = $container->get('events');
        $this->modules = new ArrayList();
    }

    public function loadModules() {
        /**
         * @var string $moduleName
         * @var Config $moduleItem
         */
        foreach ($this->config->get('modules.installed') as $moduleName => $moduleItem) {
            if ($this->isActive($moduleName)) {
                /** @var Module $module */
                $module = new $moduleItem['class']($this->container);
                $this->config->loadModuleConfig($module);
                if ($module->hasTemplates()) {
                    $moduleDir = $moduleItem->get('dir', "modules/{$moduleName}/");
                    $this->container->templates->addPath($moduleDir . $module->getTemplateDir(), $moduleName);
                }
                $this->modules->put($module);
            }
        }
        $this->events->emit("onModulesLoad");
    }

    public function run() {
        $this->modules->stream()->each(function (Module $module) {
            if ($module->isNeeded()) {
                $module->run();
            }
        });
        $this->events->emit("onModulesSetup");
    }

    public function all(): ListInterface {
        $repository = new ArrayList($this->config->get('modules.installed')->toArray(true));
        foreach ($repository as $moduleName => &$moduleInfo) {
            if (in_array($moduleName, $this->config->get('modules.active'))) {
                $moduleInfo["active"] = true;
            }
        }
        return $repository;
    }

    public function getLoadedModules(): ListInterface {
        return $this->modules;
    }

    public function findModule(string $moduleName) {
        return $this->modules->filter(function (Module $module) use ($moduleName) {
            return $module->getName() === $moduleName;
        })->first();
    }

    public function isActive(string $moduleName): bool {
        return in_array($moduleName, $this->config->get('modules.active'));
    }

    public function installer(): ModuleInstaller {
        if (!isset($this->installer)) {
            $this->installer = new ModuleInstaller($this->container);
        }
        return $this->installer;
    }
}