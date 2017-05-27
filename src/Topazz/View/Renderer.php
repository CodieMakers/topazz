<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Environment;
use Topazz\View\Extension\ModulesExtension;
use Topazz\View\Extension\ParsedownExtension;
use Topazz\View\Extension\SecurityExtension;

class Renderer {

    private static $instance;
    protected $twig;
    protected $templateLoader;
    protected $defaultData = [];

    protected function __construct() {
        $this->templateLoader = new \Twig_Loader_Filesystem("templates");
        $this->twig = new \Twig_Environment($this->templateLoader, [
            'cache' => Environment::isProduction() ? getcwd() . "/storage/cache/twig" : false
        ]);
        $this->twig->addExtension(new ModulesExtension());
        $this->twig->addExtension(new SecurityExtension());
        $this->twig->addExtension(new ParsedownExtension());
    }

    public function registerTemplateDir(string $templateDir, string $namespace = null) {
        $this->templateLoader->addPath($templateDir, $namespace);
    }

    public function addExtension(\Twig_Extension $extension) {
        $this->twig->addExtension($extension);
    }

    public function render(Request $request, Response $response, string $template, $data = []): Response {
        $this->defaultData = array_merge($this->defaultData, $request->getAttributes());
        $response = $response->write(
            $this->twig->render($template, array_merge($this->defaultData, $data))
        );
        return $response->withStatus(200);
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Renderer();
        }
        return self::$instance;
    }
}