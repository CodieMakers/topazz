<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Router;
use Slim\Views\Twig;
use Topazz\Application;

abstract class Module {

    /** @var ContainerInterface $container */
    protected $container;
    /** @var Twig $view */
    protected $view;
    /** @var App $router */
    protected $router;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->router = Application::getInstance();
    }

    /**
     * @return boolean Returns TRUE if this module is enabled, otherwise FALSE.
     */
    abstract public function isEnabled();

    /**
     * @return void
     */
    abstract public function setup();
}