<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Controller\Controller;
use Topazz\Database\Optional;
use Topazz\Entity\User;

class AuthController extends Controller {

    public function login(Request $request, Response $response) {
        if ($request->isGet()) {
            return $this->view->render($response, "admin/login.twig");
        }
        $uri = $request->getQueryParam("return_url");
        /** @var Optional $user */
        $user = User::findBy("username", $request->getParsedBodyParam("username"))->first();
        if ($user->isNull()) {
            return $this->view->render($response, "admin/login.twig", [
                "error_type" => "user_not_found",
                "username" => $request->getParsedBodyParam("username"),
                "remember-me" => $request->getParsedBodyParam("remember-me")
            ]);
        }
        $user = $user->orNull(); //there cannot be null
        /** @var User $user */
        if (!$user->matchPassword($request->getParsedBodyParam("password"))) {
            return $this->view->render($response, "admin/login.twig", [
                "error_type" => "wrong_password",
                "username" => $request->getParsedBodyParam("username"),
                "remember-me" => $request->getParsedBodyParam("remember-me")
            ]);
        }
        if ($request->getParsedBodyParam("remember-me") == "on") {
            $_SESSION["user_id"] = $user->id;
        } else {
            setcookie("user_id", $user->id);
        }
        return $response->withheader("Location", isset($uri) ? $uri : '/admin');
    }

    public function logout(Request $request, Response $response): Response {
        unset($_SESSION["user_id"]);
        unset($_COOKIE["user_id"]);
        $loginUri = $this->container->get("router")->pathFor('login');
        return $response->withStatus(200)->withRedirect($loginUri);
    }
}