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

class AuthController extends Controller {

    public function login(Request $request, Response $response) {
        if (is_null($request->getParsedBodyParam("username"))) {
            return $this->view->render($response, "admin/login.twig");
        }
        $uri = $request->getQueryParam("return_url");
        if ($request->getParsedBodyParam("remember-me") == "on") {
            $_SESSION["user_id"] = 1;
        } else {
            setcookie("user_id", 1);
        }
        return $response
            ->withStatus(303)
            ->withheader("Location", isset($uri) ? $uri : '/admin');
    }
}