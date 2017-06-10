<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Service;


use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoggerServiceProvider implements ServiceProviderInterface {

    public function register(Container $pimple) {
        $pimple["logger"] = function () {
            return new Logger("topazz", [new RotatingFileHandler("storage/log/app.log", 5)]);
        };
    }
}