<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Topazz\Data\Filesystem;
use Topazz\Event\Event;
use Topazz\Event\EventListener;

class Configuration implements EventListener {

    private static $CONFIG_FILE = "config.yml";

    protected $configs;
    protected $moduleConfigs = [];
    private $moduleNames = [];

    public function __construct(Container $container) {
        $this->configs = Filesystem::parseYAML(self::$CONFIG_FILE);
        $container->get('events')->subscribe("onShutdown", $this);
    }

    public function get(string $key) {
        try {
            return $this->findLayer($key)[$this->getLastKey($key)];
        } catch (ConfigurationNotFoundException $exception) {
            return null;
        }
    }

    public function set(string $key, $value) {
        $configs = $this->findLayer($key);
        var_dump($configs);
        $configs[$this->getLastKey($key)] = $value;
        var_dump($configs);
    }

    public function add(string $key, $value) {
        $config = $this->findLayer($key);
        $lastKey = $this->getLastKey($key);
        if (is_array($config[$lastKey])) {
            $config[$lastKey][] = $value;
        } else {
            $lastValue = $config[$lastKey];
            $config[$lastKey] = [$lastValue, $value];
        }
    }

    public function remove($key, $value = null) {
        $config = $this->findLayer($key);
        if (is_array($config)) {
            // TODO: implement removing config
        }
    }

    public function exists(string $key): bool {
        try {
            $config = $this->findLayer($key);
            return isset($config[$this->getLastKey($key)]);
        } catch (ConfigurationNotFoundException $e) {
            return false;
        }
    }

    protected function getLastKey(string $key): string {
        $keys = explode(".", $key);
        return $keys[count($keys) - 1];
    }

    protected function findLayer(string $key) {
        $keyParts = explode(".", $key);
        $lastLevel = &$this->configs;
        if (in_array($keyParts[0], $this->moduleNames)) {
            $lastLevel = &$this->moduleConfigs;
        }
        for ($i = 0, $len = count($keyParts) - 1; $i < $len; $i++) {
            $part = $keyParts[$i];
            if (isset($lastLevel[$part]) && is_array($lastLevel[$part])) {
                $lastLevel = &$lastLevel[$part];
            } else {
                throw new ConfigurationNotFoundException($key);
            }
        }
        return $lastLevel;
    }

    public function loadModuleConfig(string $moduleName) {
        if (in_array($moduleName, $this->moduleNames)) return;
        $this->moduleNames[] = $moduleName;
        $this->moduleConfigs[$moduleName] = Filesystem::parseYAML("modules/{$moduleName}/" . self::$CONFIG_FILE);
    }

    protected function onShutdown(Event $event): Event {
        Filesystem::writeYAML(self::$CONFIG_FILE, $this->configs);
        foreach ($this->moduleConfigs as $moduleName => $moduleConfig) {
            Filesystem::writeYAML("modules/{$moduleName}/" . self::$CONFIG_FILE, $moduleConfig);
        }
        return $event;
    }

    public static function register(Container $container) {
        $container["config"] = function ($c) {
            return new Configuration();
        };
    }
}