<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Service;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Csrf\Guard;

class SecurityServiceProvider implements ServiceProviderInterface {

    public function register(Container $container) {
        $container["guard"] = function () {
            return new Guard();
        };
    }
}