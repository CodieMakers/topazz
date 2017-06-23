<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Monolog\Logger;
use Slim\Container as SlimContainer;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Topazz\Config\Configuration;
use Topazz\Data\ResourceFinder;
use Topazz\Database\Connector;
use Topazz\Event\EventEmitter;
use Topazz\Module\ModuleManager;
use Topazz\Service\HttpServiceProvider;
use Topazz\Service\LoggerServiceProvider;
use Topazz\Service\Security;
use Topazz\Theme\ThemeManager;
use Topazz\View\Breadcrumbs;
use Topazz\View\Markdown;
use Topazz\View\Renderer;
use Topazz\View\TemplateLoader;

/**
 * Class Container
 *
 * @package Topazz
 * @property Logger         logger
 * @property Messages       flash
 * @property EventEmitter   events
 * @property Configuration  config
 * @property TemplateLoader templates
 * @property Renderer       renderer
 * @property Connector      connector
 * @property ModuleManager  modules
 * @property ThemeManager   themes
 * @property Markdown       markdown
 * @property Breadcrumbs    breadcrumbs
 * @property Security       security
 */
class Container extends SlimContainer {

    private static $services = [
        LoggerServiceProvider::class,
        HttpServiceProvider::class,
        "events" => EventEmitter::class,
        "config" => Configuration::class,
        "templates" => TemplateLoader::class,
        "renderer" => Renderer::class,
        "connector" => Connector::class,
        "modules" => ModuleManager::class,
        "themes" => ThemeManager::class,
        "markdown" => Markdown::class,
        "breadcrumbs" => Breadcrumbs::class,
        "security" => Security::class
    ];

    public function __construct($settings = []) {
        parent::__construct(["settings" => $settings]);
    }

    public function loadServices() {
        foreach (self::$services as $key => $serviceClass) {
            if (is_int($key)) {
                $this->register(new $serviceClass);
            } else {
                $this->registerService($key, $serviceClass);
            }
        }
    }

    protected function registerService(string $serviceKey, string $serviceClass) {
        $this[$serviceKey] = function ($c) use ($serviceClass) {
            return new $serviceClass($c);
        };
    }
}