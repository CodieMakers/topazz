<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Topazz\Container;
use Topazz\Data\RandomStringGenerator;

class SecurityExtension extends \Twig_Extension {

    private $container;
    private $request;
    private $security;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->security = $container->security;
        $this->request = $container->get('request');
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction("csrf_input", [$this, "getCsrfInput"], ["is_safe"=> ["html"]]),
            new \Twig_SimpleFunction("nonce_input", [$this, "getNonceInput"], ["is_safe"=> ["html"]]),
            new \Twig_SimpleFunction("nonce", [$this, "generateNonce"])
        ];
    }

    public function getCsrfInput() {
        $nameKey = $this->security->guard()->getTokenNameKey();
        $valueKey = $this->security->guard()->getTokenValueKey();
        $name = $this->request->getAttribute($nameKey);
        $value = $this->request->getAttribute($valueKey);
        return
            "<input type='hidden' name='$nameKey' value='$name'/>" .
            "<input type='hidden' name='$valueKey' value='$value'/>";
    }

    public function generateNonce() {
        return $this->security->nonce();
    }

    public function getNonceInput() {
        $nonce = $this->generateNonce();
        return "<input type='hidden' name='nonce' value='$nonce'/>";
    }
}