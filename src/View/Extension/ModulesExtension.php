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

class ModulesExtension extends \Twig_Extension {

    protected $container;
    /** @var ModuleManager $moduleManager */
    protected $moduleManager;
    protected $assets = [
        "css" => [],
        "js" => []
    ];

    public function __construct(Container $container) {
        $this->container = $container;
        $this->moduleManager = Application::getInstance()->getModuleManager();
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction("addCss", [$this, "addCssResource"]),
            new \Twig_SimpleFunction("addJs", [$this, "addJsResource"]),
            new \Twig_SimpleFunction("assets", [$this, "renderAssets"]),
        ];
    }

    public function addCssResource(string $resourceUrl) {
        $this->assets["css"][] = $resourceUrl;
    }

    public function addJsResource(string $resourceUrl) {
        $this->assets["js"][] = $resourceUrl;
    }

    public function renderAssets() {
        $cssLinks = "";
        foreach ($this->assets["css"] as $css) {
            $cssLinks .= "<link rel=\"stylesheet\" href='$css'>" . PHP_EOL;
        }
        $jsScripts = "";
        foreach ($this->assets["js"] as $js) {
            $jsScripts .= "<script async type='application/javascript' src='$js'></script>" . PHP_EOL;
        }

        return $cssLinks . $jsScripts;
    }
}