<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Container;
use Topazz\Data\Collections\Maps\HashMap;
use Topazz\Data\Optional;

class ThemeManager {

    protected $container;
    protected $themes;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->themes = new HashMap();
    }

    public function loadTheme(string $name) {
        $config = $this->container->config;
        if ($config->exists("themes.installed.{$name}")) {
            $installedTheme = $config->get('themes.installed.' . $name);
            if (in_array($name, $config->get("themes.active"))) {
                $this->themes->set($name, new $installedTheme["class"]($this->container));
            }
        }
    }

    public function findTheme(string $name): Optional {
        return $this->themes->get($name);
    }
}