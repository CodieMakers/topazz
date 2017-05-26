<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Admin\Controller\AuthController;
use Topazz\Admin\Controller\ModuleController;
use Topazz\Admin\Controller\UserController;
use Topazz\Admin\Middleware\Authentication;
use Topazz\Admin\Middleware\Authorization;
use Topazz\Admin\Middleware\IpRestriction;
use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\ModuleWithTemplates;

class Administration extends ModuleWithTemplates {

    public $templateDir = "templates/admin";

    public function isActivated() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return $_SERVER['HTTP_HOST'] === getenv('ADMIN_HOST');
    }

    public function index(Request $request, Response $response) {
        return $this->view->render($request, $response, '@admin/index.twig');
    }

    /**
     * @return void
     */
    public function setup() {
        parent::setup();
        $this->router->group(getenv('ADMIN_URI'), function () {
            $this->get("", Administration::class . ":index");

            $this->get("/users", UserController::class . ':index');
            $this->get("/user/{id:[0-9]*}", UserController::class . ':detail');
            $this->post("/user/{id:[0-9]*}", UserController::class . ':saveDetail');
            $this->any("/user/{id:[0-9]*}/delete", UserController::class . ':delete');

            $this->get('/modules', ModuleController::class . ':index')->add(Authorization::withPermission('modules.list'));
            $this->group('/module/{moduleName}', function () {
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
            ->add(TemplateConfigMiddleware::withPageTitle('Administration'));

        $this->router->map(["GET", "POST"], "/login", AuthController::class . ":login")
            ->add(TemplateConfigMiddleware::withBodyClass("login"))->setName("login");

        $this->router->get("/logout", AuthController::class . ":logout")
            ->add(TemplateConfigMiddleware::withBodyClass("logout"))->setName("logout");

        $this->router->post("/register", AuthController::class . ":register")
            ->add(TemplateConfigMiddleware::withBodyClass("register"))->setName("register");
    }
}