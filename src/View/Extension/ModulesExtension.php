<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Topazz\Application;
use Topazz\Container;
use Topazz\Module\ModuleManager;
use Topazz\View\AssetManager;

class ModulesExtension extends \Twig_Extension {

    protected $container;
    /** @var ModuleManager $moduleManager */
    protected $moduleManager;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->moduleManager = $container->get('modules');
    }

    public function getFunctions() {
        return [];
    }
}