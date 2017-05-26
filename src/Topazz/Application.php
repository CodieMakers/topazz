<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Dotenv\Dotenv;
use Topazz\Database\Connector;
use Topazz\View\Twig;

class Application extends App {

    private static $instance;
    /** @var Container $container */
    private $container;
    /** @var Dotenv $environment */
    private $environment;

    public function __construct() {
        self::$instance = $this;
        $this->environment = new Dotenv();
        if (file_exists(".env")) {
            $this->environment->load(".env");
        } else {
            $this->environment->populate(["ENV" => "installation"]);
        }
        $this->container = new Container(['settings' => [
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => !self::isProduction()
        ]]);
        session_start();
        if (self::isProduction()) {
            $this->container['notFoundHandler'] = function (Container $container) {
                return function ($request, $response) use ($container) {
                    return $container->renderer()->render($request, $response, "error/404.twig");
                };
            };
            $this->container['errorHandler'] = function (Container $container) {
                return function ($request, $response) use ($container) {
                    return $container->renderer()->render($request, $response, "error/exception.twig");
                };
            };
        }
        parent::__construct($this->container);
    }

    public static function getInstance(): Application {
        if (is_null(self::$instance)) {
            throw new ApplicationException(ApplicationException::NOT_INIT);
        }
        return self::$instance;
    }

    public static function isProduction() {
        return getenv("ENV") == "production";
    }

    public function run($silent = false) {
        $this->container->moduleManager()->run();
        parent::run($silent);
    }

    public function getContainer() {
        return $this->container;
    }

    public function getEnvironmentLoader() {
        return $this->environment;
    }
}