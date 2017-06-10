<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Composer\Autoload\ClassLoader;
use Slim\App;
use Topazz\Event\EventEmitter;
use Topazz\Middleware\ProjectResolver;
use Topazz\Module\ModuleManager;
use Topazz\Service\LoggerServiceProvider;
use Topazz\Service\SecurityServiceProvider;
use Topazz\View\Renderer;

class Application {

    private static $instance;

    private static $services = [
        LoggerServiceProvider::class,
        "events" => EventEmitter::class,
        "config" => Configuration::class,
        "renderer" => Renderer::class,
        "modules" => ModuleManager::class,
        SecurityServiceProvider::class
    ];

    /** @var App $app */
    protected $app;
    /** @var Container $container */
    protected $container;

    public function __construct(ClassLoader $classLoader) {
        self::$instance = $this;

        Environment::load();

        $this->container = new Container([
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => !Environment::isProduction()
        ]);
        $this->app = new App($this->container);

        $this->app->add(EventEmitter::emitEventAfterMiddleware("onShutdown"));

        foreach (self::$services as $key => $serviceClass) {
            if (is_int($key)) {
                $this->container->register(new $serviceClass);
            } else {
                $this->container->registerService($key, $serviceClass);
            }
        }

        $this->app->add(new ProjectResolver());
        $this->container->get('events')->emit("onInit");
    }

    public function run() {
        $this->container->get('modules')->run();
        return $this->app->run(false);
    }

    public function getApp(): App {
        return $this->app;
    }

    public static function getInstance(): Application {
        return self::$instance;
    }
}