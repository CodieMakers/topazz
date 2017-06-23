<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Auth;


use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Container;
use Topazz\Middleware\SecurityMiddleware;
use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\Module;

class AuthModule extends Module {

    protected $name = "auth";
    protected $application;
    protected $events;

    public function __construct(Container $container) {
        parent::__construct($container);
        $this->application = Application::getInstance()->getApp();
        $this->events = $container->events;
    }

    public function isNeeded(): bool {
        return true;
    }

    public function hasTemplates(): bool {
        return true;
    }

    public function getTemplateDir(): string {
        return 'templates/auth';
    }

    public function hasConfig(): bool {
        return false;
    }

    public function getConfigFilename(): string {
        return '';
    }

    public function run() {
        // ------------ AUTH
        $this->application->group("/auth", function () {
            /** @var App $this */
            $this->get('/login', AuthController::class . ':index')->setName('login');
            $this->post('/login', AuthController::class . ':login');
            $this->get('/logout', AuthController::class . ':logout')->setName('logout');
        })->add(TemplateConfigMiddleware::withBodyClass('auth'));

        $this->events->emit("onAuthInit");
    }
}