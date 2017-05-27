<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin;


use Slim\App;
use Topazz\Admin\Controller\AuthController;
use Topazz\Admin\Controller\DashboardController;
use Topazz\Admin\Controller\ModuleController;
use Topazz\Admin\Controller\UserController;
use Topazz\Admin\Middleware\Authentication;
use Topazz\Admin\Middleware\Authorization;
use Topazz\Admin\Middleware\IpRestriction;
use Topazz\Application;
use Topazz\Environment;
use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\Module;
use Topazz\View\Renderer;

class AdministrationModule extends Module {

    public $templateDir = "templates/admin";

    public function isActivated() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return $_SERVER['HTTP_HOST'] === Environment::get('ADMIN_HOST');
    }

    /**
     * @return void
     */
    public function setup() {
        $this->container->get('view')
            ->registerTemplateDir($this->templateDir, "admin");

        $this->application->group(Environment::get('ADMIN_URI'), function () {
            /** @var App $this */
            $this->get("", DashboardController::class . ":index");

            $this->get("/users", UserController::class . ':index');
            $this->get("/user/{id:[0-9]*}", UserController::class . ':detail');
            $this->post("/user/{id:[0-9]*}", UserController::class . ':saveDetail');
            $this->any("/user/{id:[0-9]*}/delete", UserController::class . ':delete');

            $this->get('/modules', ModuleController::class . ':index')->add(Authorization::withPermission('modules.list'));
            $this->group('/module/{moduleName}', function () {
                /** @var App $this */
                $this->get('', ModuleController::class . ':detail');
                $this->post('/install', ModuleController::class . ':install');
                $this->post('/remove', ModuleController::class . ':remove');
                $this->post('/enable', ModuleController::class . ':enable');
                $this->post('/disable', ModuleController::class . ':disable');

            })->add(Authorization::withPermission('modules.edit'));

        })
            ->add(new IpRestriction())
            ->add(new Authentication())
            ->add($this->container->get('csrf'))
            ->add(TemplateConfigMiddleware::withBodyClass("admin"))
            ->add(TemplateConfigMiddleware::withPageTitle('AdministrationModule'));

        $this->application->map(["GET", "POST"], "/login", AuthController::class . ":login")
            ->add(TemplateConfigMiddleware::withBodyClass("login"))->setName("login");

        $this->application->get("/logout", AuthController::class . ":logout")
            ->add(TemplateConfigMiddleware::withBodyClass("logout"))->setName("logout");

        $this->application->post("/register", AuthController::class . ":register")
            ->add(TemplateConfigMiddleware::withBodyClass("register"))->setName("register");
    }

    public function create() {
        // TODO: implement create() method.
    }

    public function update() {
        // TODO: Implement update() method.
    }

    public function remove() {
        // TODO: Implement remove() method.
    }
}