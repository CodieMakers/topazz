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
    private $container;

    public function __construct() {
        self::$instance = $this;
        (new Dotenv())->load(".env");
        $this->container = new Container(['settings' => [
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => !$this->isProduction()
        ]]);
        session_start();
        $this->container['logger'] = function ($container) {
            $logger = new Logger("Topazz");
            if (getenv("ENV") != "production") {
                $logger->pushHandler(new StreamHandler("php://stdout", Logger::DEBUG));
            }
            $logger->pushHandler(new RotatingFileHandler("storage/log/app.log", 5, Logger::CRITICAL));
            return $logger;
        };
        $this->container['flash'] = function ($container) {
            return new Messages();
        };
        $this->container['csrf'] = function ($container) {
            return new Guard();
        };
        $this->container['view'] = function ($container) {
            return new Twig($container, [
                'cache' => false
            ]);
        };
        $this->container["db"] = function ($container) {
            return new Connector();
        };
        if ($this->isProduction()) {
            $this->container['notFoundHandler'] = function ($container) {
                return function ($request, $response) use ($container) {
                    return $container->get('view')->render($response, "error/404.twig");
                };
            };
            $this->container['errorHandler'] = function ($container) {
                return function ($request, $response) use ($container) {
                    return $container->get('view')->render($response, "error/exception.twig");
                };
            };
        } else {
            setcookie("XDEBUG_SESSION", "13011");
//            $this->add(function (Request $request, Response $response, $next) {
//                return $next($request, $response->withHeader(
//                    "Set-Cookie", "XDEBUG_SESSION=13011;"
//                ));
//            });
        }

        parent::__construct($this->container);
    }

    public static function getInstance(): Application {
        if (is_null(self::$instance)) {
            throw new ApplicationException(ApplicationException::NOT_INIT);
        }
        return self::$instance;
    }

    public function isProduction() {
        return getenv("ENV") == "production";
    }

    /**
     * @param bool $silent
     *
     * @return void
     */
    public function run($silent = false) {
        $this->getContainer()->getModuleManager()->run();
        parent::run($silent);
    }

    /**
     * @return Container
     */
    public function getContainer() {
        return $this->container;
    }
}