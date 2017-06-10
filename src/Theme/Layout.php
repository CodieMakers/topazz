<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


abstract class Layout {

    public $name;
    public $template;

    abstract public function render(array $posts = []): string;

}