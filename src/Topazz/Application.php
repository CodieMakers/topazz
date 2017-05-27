<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Slim\App;
use Topazz\Module\ModuleManager;

class Application extends App {

    private static $instance;

    public function __construct() {
        self::$instance = $this;
        Environment::loadFromFile();
        session_start();
        parent::__construct(['settings' => [
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => !Environment::isProduction()
        ]]);
    }

    public function run($silent = false) {
        ModuleManager::getInstance()->run();
        return parent::run($silent);
    }

    public static function getInstance(): Application {
        if (is_null(self::$instance)) {
            throw new ApplicationException(ApplicationException::NOT_INIT);
        }
        return self::$instance;
    }
}