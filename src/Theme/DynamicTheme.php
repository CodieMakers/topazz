<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


abstract class DynamicTheme extends Theme {

    abstract public function getTemplatesDir(): string;
}