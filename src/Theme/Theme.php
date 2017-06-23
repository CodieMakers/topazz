<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Container;

abstract class Theme {

    protected static $templateDir;

    protected $name;
    protected $parent;
    protected $layouts = [];

    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
        if (isset(static::$templateDir)) {
            $container->templates->addPath(static::$templateDir, $this->getName());
        }
    }

    public function getName() {
        return $this->name;
    }
}