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
use Topazz\View\Extension\MarkdownExtension;
use Topazz\View\Extension\SecurityExtension;

class Renderer {

    protected $twig;
    protected $defaultData = [];

    public function __construct(Container $container) {
        $this->defaultData["assets"] = new AssetManager();
        $this->defaultData["breadcrumbs"] = $container->breadcrumbs;
        $this->twig = new \Twig_Environment($container->templates, [
            'cache' => Environment::isProduction() ? getcwd() . "/storage/cache/twig" : false,
            'debug' => !Environment::isProduction()
        ]);
        $this->twig->addExtension(new ModulesExtension($container));
        $this->twig->addExtension(new SecurityExtension($container));
        $this->twig->addExtension(new FlashStorageExtension($container));
        $this->twig->addExtension(new MarkdownExtension($container));
    }

    public function addExtension(\Twig_Extension $extension) {
        $this->twig->addExtension($extension);
    }

    public function display(Request $request, Response $response, string $template, $data = []): Response {
        $this->defaultData = array_merge($this->defaultData, $request->getAttributes());
        $response->getBody()->write(
            $this->twig->render($template, array_merge($this->defaultData, $data))
        );
        return $response;
    }

    public function render(string $template, $data = []): string {
        return $this->twig->render($template, array_merge($this->defaultData, $data));
    }
}