<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Topazz\Container;
use Topazz\Module\ModuleManager;

class TopazzModuleTwigExtension extends \Twig_Extension {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction("module_path", [$this, "getModulePath"])
        ];
    }

    /**
     * @param string $moduleName
     *
     * @return string
     * @throws \Twig_Error_Runtime
     */
    public function getModulePath(string $moduleName) {
        $module = $this->moduleManager->findModule($moduleName);
        if (is_null($module)) {
            throw new \Twig_Error_Runtime(sprintf("There is no such a module like %s", $moduleName));
        }
        return "/modules/" . $module->name . "/";
    }
}