<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Knlv\Slim\Views\TwigMessages;
use Slim\Http\Request;
use Slim\Views\Twig as TwigView;
use Slim\Views\TwigExtension;
use Topazz\Container;

class Twig extends TwigView {

    public function __construct(Container $container, $settings = []) {
        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        parent::__construct("templates", $settings);
        $this->addExtension(new TwigExtension($container['router'], $basePath));
        $this->addExtension(new TwigCsrfExtension($container->get('csrf'), $container->get('request')));
        $this->addExtension(new TwigMessages($container['flash']));
    }

    public function withRequest(Request $request) {
        $this->defaultVariables = array_merge($this->defaultVariables, $request->getAttributes());
        return $this;
    }

    public function registerModuleTemplatesDir(string $templateDir, string $moduleName = null) {
        $this->loader->addPath($templateDir, $moduleName);
    }
}