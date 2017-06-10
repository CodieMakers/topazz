<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Topazz\Theme\DynamicTheme;

class TemplateLoader extends \Twig_Loader_Filesystem {

    public function registerTheme(DynamicTheme $theme) {
        $this->addPath($theme->getTemplatesDir(), $theme->getName());
    }
}