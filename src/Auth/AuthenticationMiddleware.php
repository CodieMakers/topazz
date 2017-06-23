<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Auth;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Entity\User;

class AuthenticationMiddleware {

    public static function withRedirect(string $uri) {
        return function (Request $request, Response $response, callable $next) use ($uri): Response {
            $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : $request->getCookieParam("user_id");
            if (!is_null($userId) && !empty($userId) && $userId !== "invalid") {
                $user = User::find("uuid", $userId)->first()->orNull();
                if (!is_null($user)) {
                    return $next($request->withAttribute("current_user", $user), $response);
                }
            }
            $router = Application::getInstance()->getApp()->getContainer()->get('router');
            $query = $request->getUri()->getQuery();
            $loginUri = $request->getUri()->withPath($router->pathFor("login"))
                ->withQuery("return_uri=" . $uri . (!empty($query) ? "&" . $query : ""));
            return $response->withRedirect($loginUri, 403);
        };
    }
}