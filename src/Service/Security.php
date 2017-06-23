<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Service;


use Slim\Csrf\Guard;
use Topazz\Container;
use Topazz\Data\RandomStringGenerator;

class Security {

    protected $container;
    protected $guard;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->guard = new Guard();
        $this->guard->setPersistentTokenMode(true);
    }

    public function guard() {
        return $this->guard;
    }

    public function nonce() {
        $nonce = RandomStringGenerator::generate(30);
        $this->container->flash->addMessage('nonce', $nonce);
        return $nonce;
    }

    public function getCsrfToken() {
        $nameKey = $this->guard->getTokenNameKey();
        $valueKey = $this->guard->getTokenValueKey();
        $name = $this->guard->getTokenName();
        $value = $this->guard->getTokenValue();
        return [
            $nameKey => $name,
            $valueKey => $value
        ];
    }
}