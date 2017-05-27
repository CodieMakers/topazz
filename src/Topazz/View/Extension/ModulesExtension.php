<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Topazz\Module\ModuleManager;

class ModulesExtension extends \Twig_Extension {

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
        $module = ModuleManager::getInstance()->findModule($moduleName);
        if (is_null($module)) {
            throw new \Twig_Error_Runtime(sprintf("There is no such a module like '%s'", $moduleName));
        }
        return "/modules/" . $module->name . "/";
    }
}