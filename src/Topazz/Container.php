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
use Slim\Container as SlimContainer;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Topazz\Database\Connector;
use Topazz\Module\ModuleManager;
use Topazz\View\Renderer;
use Topazz\View\Twig;

class Container extends SlimContainer {

    private $modules;

    public function __construct(array $values = []) {
        parent::__construct($values);

        $this['logger'] = function ($container) {
            $logger = new Logger("Topazz");
            if (!Application::isProduction()) {
                $logger->pushHandler(new StreamHandler("php://stdout", Logger::DEBUG));
            }
            $logger->pushHandler(new RotatingFileHandler("storage/log/app.log", 5, Logger::CRITICAL));
            return $logger;
        };
        $this['flash'] = function ($container) {
            return new Messages();
        };
        $this['csrf'] = function ($container) {
            return new Guard();
        };
        $this['view'] = function ($container) {
            return new Twig($container, [
                'cache' => Application::isProduction() ? 'storage/cache/twig' : false
            ]);
        };
        $this['renderer'] = function ($container) {
            return new Renderer($container);
        };
        $this['db'] = function () {
            return new Connector();
        };
    }

    public function moduleManager() {
        if (is_null($this->modules)) {
            $this->modules = new ModuleManager($this);
        }
        return $this->modules;
    }

    /**
     * @return Connector|null
     */
    public function db() {
        return $this->get('db');
    }

    /**
     * @return Messages|null
     */
    public function flash() {
        return $this->get('flash');
    }

    /**
     * @return Renderer|null
     */
    public function renderer() {
        return $this->get('renderer');
    }
}