<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Knlv\Slim\Views\TwigMessages;
use Slim\Views\Twig as TwigView;
use Slim\Views\TwigExtension;
use Topazz\Container;

class Twig extends TwigView {

    public function __construct(Container $container, $settings = []) {
        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        parent::__construct("templates", $settings);
        $this->addExtension(new TwigExtension($container['router'], $basePath));
        $this->addExtension(new TwigGlobalsExtension($container->request));
        $this->addExtension(new TwigMessages(
            $container['flash']
        ));
    }
}