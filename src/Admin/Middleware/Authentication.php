<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Middleware;


use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\ApplicationException;
use Topazz\Entity\User;
use Topazz\Middleware\MiddlewareInterface;

class Authentication implements MiddlewareInterface {

    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface {
        if ($request->isOptions()) {
            return $next($request, $response);
        }
        $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : $request->getCookieParam("user_id");
        if (is_null($userId) || (is_string($userId) && empty($userId))) {
            $router = Application::getInstance()->getContainer()->router;
            $query = $request->getUri()->getQuery();
            $loginUri = $request->getUri()->withPath($router->pathFor("login"))
                ->withQuery("return_uri=" . $request->getUri()->getPath() . (!empty($query) ? "&" . $query : ""));
            return $response->withRedirect($loginUri, 403);
        } else {
            $user = User::findById($userId)->orThrow(new ApplicationException());
            return $next($request->withAttribute("current_user", $user), $response);
        }
    }
}