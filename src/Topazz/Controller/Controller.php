<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Controller;


use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class Controller {

    protected $container;
    /** @var Twig $view */
    protected $view;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->view = $container->get('view');
    }
}