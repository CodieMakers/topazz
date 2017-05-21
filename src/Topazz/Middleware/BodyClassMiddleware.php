<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class BodyClassMiddleware {

    private $bodyClass;

    public function __construct(string $bodyClass) {
        $this->bodyClass = $bodyClass;
    }

    public function __invoke(Request $request, Response $response, callable $next): Response {
        return $next($request->withAttribute("body_class", $this->bodyClass), $response);
    }
}