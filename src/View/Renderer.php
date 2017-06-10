<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Container;
use Topazz\Environment;
use Topazz\View\Extension\FlashStorageExtension;
use Topazz\View\Extension\ModulesExtension;
use Topazz\View\Extension\ParsedownExtension;
use Topazz\View\Extension\SecurityExtension;

class Renderer {

    protected $twig;
    protected $templateLoader;
    protected $defaultData = [];

    public function __construct(Container $container) {
        $this->templateLoader = new TemplateLoader("templates");
        $this->twig = new \Twig_Environment($this->templateLoader, [
            'cache' => Environment::isProduction() ? getcwd() . "/storage/cache/twig" : false
        ]);
        $this->twig->addExtension(new ModulesExtension($container));
        $this->twig->addExtension(new SecurityExtension($container));
        $this->twig->addExtension(new FlashStorageExtension($container));
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
        $response->getBody()->write(
            $this->twig->render($template, array_merge($this->defaultData, $data))
        );
        return $response;
    }
}