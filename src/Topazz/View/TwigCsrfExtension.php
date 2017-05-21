<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Slim\Csrf\Guard;
use Slim\Http\Request;

class TwigCsrfExtension extends \Twig_Extension {

    private $request;
    private $guard;

    public function __construct(Guard $guard, Request $request) {
        $this->guard = $guard;
        $this->request = $request;
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
            '<input type="hidden" name="'.$nameKey.'" value="'.$name.'"/>'.
            '<input type="hidden" name="'.$valueKey.'" value="'.$value.'"/>';
    }
}