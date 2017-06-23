<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Config;


use Topazz\Container;
use Topazz\Data\Filesystem;
use Topazz\Environment;
use Topazz\Event\Event;
use Topazz\Event\EventListener;
use Topazz\Module\Module;

class Configuration implements ConfigInterface, EventListener {

    const KEY_SEPARATOR = ".";

    protected $CONFIG_FILE = "config.yml";
    /** @var Config $defaults */
    protected $defaults;
    protected $configs;
    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
        $settings = $container->settings;
        if (isset($settings["configFilename"])) {
            $this->CONFIG_FILE = $settings["configFilename"];
        }
        $this->defaults = new Config($this->getDefaultConfigs());
        $this->configs = Config::fromFile($this->CONFIG_FILE);

        $container->events->subscribe("onShutdown", $this);
    }

    protected function getDefaultConfigs(): array {
        $settings = $this->container->settings;
        $defaults = [];
        if (isset($settings["configDir"])) {
            $filesystem = Filesystem::fromPath($settings["configDir"]);
            foreach ($filesystem->keys() as $key) {
                if (preg_match('/(.*)\.yml$/', $key, $matches)) {
                    $defaults[$matches[1]] = Config::fromFile($settings["configDir"] . "/" . $key);
                }
            }
        }

        $defaults["db"] = new Config([]);
        $this->loadFromEnvironment("DB_HOST", "host", $defaults["db"]);
        $this->loadFromEnvironment("DB_NAME", "name", $defaults["db"]);
        $this->loadFromEnvironment("DB_USER", "username", $defaults["db"]);
        $this->loadFromEnvironment("DB_PASSWORD", "password", $defaults["db"]);
        return $defaults;
    }

    private function loadFromEnvironment(string $envKey, string $configKey, Config &$storage) {
        if (Environment::has($envKey)) {
            $storage->set($configKey, Environment::get($envKey));
        }
    }

    public function loadModuleConfig(Module $module) {
        if ($module->hasConfig()) {
            $moduleDir = $this->get(
                'modules.installed.' . $module->getName() . ".dir",
                "modules/{$module->getName()}"
            );
            $this->defaults->set(
                $module->getName(),
                Config::fromFile($moduleDir . "/" . $module->getConfigFilename())
            );
        }
    }

    public function get(string $key, $default = null) {
        if (!$this->configs->exists($key)) {
            return $this->defaults->get($key, $default);
        }
        $item = $this->configs->get($key);
        if (is_array($item) && $this->defaults->exists($key)) {
            return array_merge($this->defaults->get($key), $item);
        } elseif ($item instanceof Config && $this->defaults->exists($key)) {
            return Config::merge($this->defaults->get($key), $item);
        }
        return is_null($item) ? $default : $item;
    }

    public function set(string $key, $value) {
        $this->configs->set($key, $value);
    }

    public function exists(string $key): bool {
        if ($this->configs->exists($key)) {
            return true;
        }
        return $this->defaults->exists($key);
    }

    public function remove(string $key) {
        $this->configs->remove($key);
    }

    public function toArray($traverse = false): array {
        return $this->configs->toArray($traverse);
    }

    public function onShutdown(Event $event): Event {
        Filesystem::writeYAML($this->CONFIG_FILE, $this->toArray(true));
        return $event;
    }

    public function getIterator() {
        return new \ArrayIterator(array_merge($this->defaults->toArray(), $this->configs->toArray()));
    }
}