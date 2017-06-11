<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Controller;


use Topazz\Container;
use Topazz\View\Renderer;

class Controller {

    /** @var Container $container */
    protected $container;
    /** @var Renderer $renderer */
    protected $renderer;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->renderer = $container->get('renderer');
    }
}