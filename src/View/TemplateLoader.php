<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Topazz\Container;

class TemplateLoader extends \Twig_Loader_Filesystem {

    public function __construct(Container $container) {
        parent::__construct($container->settings["templatesDir"]);
    }
}