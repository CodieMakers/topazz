<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Controller;


use Topazz\Config\Configuration;
use Topazz\Container;
use Topazz\View\Renderer;

abstract class Controller {

    /** @var Container $container */
    protected $container;
    /** @var Renderer $renderer */
    protected $renderer;
    /** @var Configuration $config */
    protected $config;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->renderer = $container->renderer;
        $this->config = $container->config;
    }
}