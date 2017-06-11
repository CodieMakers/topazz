<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Configuration;
use Topazz\Container;
use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Event\EventEmitter;

class ModuleManager {

    /** @var Container $container */
    protected $container;
    /** @var Configuration $config */
    protected $config;
    /** @var EventEmitter $events */
    protected $events;

    protected $modules;
    protected $themes;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->config = $container->get('config');
        $this->events = $container->get('events');
        $this->modules = new ArrayList();
        $this->themes = new ArrayList();
    }

    public function loadModules() {
        foreach ($this->config->get('modules.installed') as $installedModuleConfigItem) {
            if (in_array($installedModuleConfigItem['name'], $this->config->get('modules.active'))) {
                /** @var Module $module */
                $module = new $installedModuleConfigItem['class']($this->container);
                $this->config->loadModuleConfig($module->getName());
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

    public function getModules(): ArrayList {
        return $this->modules;
    }

    public function findModule(string $moduleName) {
        $list = $this->modules->stream()->filter(function (Module $module) use ($moduleName) {
            return $module->getName() === $moduleName;
        })->toList();
        return $list->first();
    }

    public function installer(): ModuleInstaller {
        return new ModuleInstaller();
    }
}