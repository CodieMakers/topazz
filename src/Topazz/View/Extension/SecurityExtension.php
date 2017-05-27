<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Topazz\Application;

class SecurityExtension extends \Twig_Extension {

    private $request;
    private $guard;

    public function __construct() {
        $container = Application::getInstance()->getContainer();
        $this->guard = $container->get('guard');
        $this->request = $container->get('request');
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction("csrf_input", [$this, "getCsrfInput"])
        ];
    }

    public function getCsrfInput() {
        $nameKey = $this->guard->getTokenNameKey();
        $valueKey = $this->guard->getTokenValueKey();
        $name = $this->request->getAttribute($nameKey);
        $value = $this->request->getAttribute($valueKey);
        return
            '<input type="hidden" name="' . $nameKey . '" value="' . $name . '"/>' .
            '<input type="hidden" name="' . $valueKey . '" value="' . $value . '"/>';
    }
}