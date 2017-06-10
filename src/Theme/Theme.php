<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Container;
use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Entity\Page;

abstract class Theme {

    protected $name;
    protected $container;
    protected $renderer;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->renderer = $container->get('renderer');
    }

    public function getName() {
        return $this->name;
    }

    abstract public function render(Page $page): string;

    abstract public function layouts(): ArrayList;
}