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
use Topazz\Environment;
use Topazz\Middleware\MiddlewareInterface;

class IpRestriction implements MiddlewareInterface {


    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface {
        $ipRestriction = Environment::get('ADMIN_IP_RESTRICTION');
        if (isset($ipRestriction)) {

        }
        return $next($request, $response);
    }
}