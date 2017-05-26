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
use Topazz\Entity\Project;

class ProjectResolver implements MiddlewareInterface {

    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface {
        $projects = Project::findBy("uri", $request->getUri()->getHost());
        return $next($request, $response);
    }
}