<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Entity\Project;
use Topazz\Event\EventEmitter;
use Topazz\Middleware\MiddlewareInterface;

class Application implements MiddlewareInterface {

    private static $instance;

    /** @var App $app */
    protected $app;
    /** @var Container $container */
    protected $container;

    public function __construct(array $settings = []) {
        self::$instance = $this;
        Environment::load();
        $this->container = new Container(array_merge([
            'configDir' => "config",
            'configFilename' => "config.yml",
            'templatesDir' => "templates",
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => !Environment::isProduction()
        ], $settings));
        $this->app = new App($this->container);
        $this->container->loadServices();
        $this->app->add(EventEmitter::emitEventAfterMiddleware("onShutdown"));
        $this->app->add($this);
        $this->container->events->emit("onInit");
    }

    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface {
        /** @var Project $project */
        $project = Project::findByHost($request->getUri()->getHost())->orNull();
        if (!is_null($project) && $project->hasPage($request->getUri()->getPath())) {
            return $project->render($request, $response);
        }
        return $next($request, $response);
    }

    public function run() {
        $modules = $this->container->modules;
        $modules->loadModules();
        $modules->run();
        return $this->app->run(false);
    }

    public function getApp(): App {
        return $this->app;
    }

    public static function getInstance(): Application {
        return self::$instance;
    }
}