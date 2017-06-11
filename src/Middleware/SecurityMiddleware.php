<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Middleware;


use DavidePastore\Slim\RestrictRoute\RestrictRoute;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Data\RandomStringGenerator;

class SecurityMiddleware {

    public static function nonce() {
        $container = Application::getInstance()->getApp()->getContainer();
        return function (Request $request, Response $response, callable $next) use ($container): Response {
            $flash = $container->get('flash');
            $previousNonce = $request->getParsedBodyParam('nonce');
            if (is_null($previousNonce)) {
                $previousNonce = $request->getQueryParam('nonce');
            }
            if (!is_null($previousNonce) && is_string($previousNonce) && $flash->hasMessage('nonce')) {
                // TODO: implement nonce
            }
            $nonce = RandomStringGenerator::generate(30);
            $flash->addMessageNow('nonce', $nonce);
            return $next($request->withAttribute('nonce', $nonce), $response);
        };
    }

    public static function ipRestriction($options = []) {
        return new RestrictRoute($options);
    }
}