<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Middleware;


use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class BodyClassMiddleware {

    public static function withClass(string $class) {
        return function (Request $request, Response $response, callable $next) use ($class): ResponseInterface {
            return $next($request->withAttribute("body_class", $class), $response);
        };
    }
}