<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Service;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RKA\Middleware\IpAddress;
use Slim\App;
use Slim\Flash\Messages;
use Topazz\Application;
use Topazz\Environment;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

class HttpServiceProvider implements ServiceProviderInterface {

    public function register(Container $container) {
        if (!isset($_SESSION)) {
            @session_start();
        }
        $container["flash"] = function ($c) {
            return new Messages();
        };

        $app = Application::getInstance()->getApp();
        $app->add(new IpAddress());
        if (!Environment::isProduction()) {
            $app->add(new WhoopsMiddleware($app));
        }
    }
}