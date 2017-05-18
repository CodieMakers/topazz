<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin;


use Slim\Middleware\JwtAuthentication;
use Topazz\Admin\Controller\AuthController;
use Topazz\Admin\Controller\UserController;
use Topazz\Admin\Middleware\Authentication;
use Topazz\Module\Module;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Administration extends Module {

    /**
     * @return boolean
     */
    public function isEnabled() {
        return true;
    }

    public function index(Request $request, Response $response) {
        $user = $request->getParsedBody();
        return $this->view->render($response, 'admin/index.twig');
    }

    /**
     * @return void
     */
    public function setup() {
        $this->router->group("/admin", function () {
            $this->get("", Administration::class . ":index");
            $this->get("/users", UserController::class . ':index');
            $this->get("/user/{id}", UserController::class . ':detail');
        })->add(new Authentication());
        $this->router->map(["GET", "POST"],"/login", AuthController::class . ":login")->setName("login");
    }
}