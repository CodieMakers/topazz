<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Container;
use Topazz\Data\Collections\ListInterface;

abstract class Layout {

    protected static $name;
    protected $container;
    protected $renderer;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function getName(): string {
        return self::$name;
    }

    abstract public function render(ListInterface $posts): string;

}