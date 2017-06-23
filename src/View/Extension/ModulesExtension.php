<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Topazz\Container;
use Topazz\Module\ModuleManager;

class ModulesExtension extends \Twig_Extension {

    protected $container;
    /** @var ModuleManager $moduleManager */
    protected $moduleManager;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->moduleManager = $container->modules;
    }

    public function getFunctions() {
        return [
            new \Twig_Function("add_breadcrumb", [$this, "addBreadcrumb"]),
            new \Twig_Function("render_breadcrumbs", [$this, "renderBreadcrumbs"], ["is_safe" => ["html"]]),
            new \Twig_Function("module_active", [$this, "isModuleActive"])
        ];
    }

    public function getFilters() {
        return [
        ];
    }

    public function getTests() {
        return [

        ];
    }

    public function addBreadcrumb(string $label, string $uri) {
        $this->container->breadcrumbs->add($label, $uri);
    }

    public function renderBreadcrumbs() {
        return (string)$this->container->breadcrumbs;
    }

    public function isModuleActive(string $moduleName) {
        return $this->moduleManager->isActive($moduleName);
    }
}