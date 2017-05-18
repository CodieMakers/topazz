<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Entity\User;

class Authentication {

    public function __invoke(Request $request, Response $response, callable $next) {
        if (!isset($_SESSION["user_id"]) || is_null($request->getCookieParam("user_id"))) {
            $router = Application::getInstance()->getContainer()->router;
            $loginUri = $request->getUri()->withPath($router->pathFor("login"))
                ->withQuery("return_uri=" . $request->getUri()->getPath());
            return $response->withRedirect($loginUri, 403);
        } else {
            //$user = User::findById($request->getAttribute("user_id"));
            return $next($request, $response);
        }
    }
}