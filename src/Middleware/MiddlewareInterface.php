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

interface MiddlewareInterface {

    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface;
}