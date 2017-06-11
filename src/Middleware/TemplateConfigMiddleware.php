<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class TemplateConfigMiddleware {

    public static function withPageTitle(string $pageTitle) {
        return self::with('page_title', $pageTitle);
    }

    public static function withBodyClass(string $class) {
        return self::with('body_class', $class);
    }

    private static function with($configName, $value) {
        return function (Request $request, Response $response, callable $next) use ($configName, $value) {
            return $next($request->withAttribute($configName, $value), $response);
        };
    }
}