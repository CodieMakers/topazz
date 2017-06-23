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
use Topazz\Container;
use Topazz\Data\RandomStringGenerator;

class SecurityMiddleware {

    public static function nonce() {
        /** @var Container $container */
        $container = Application::getInstance()->getApp()->getContainer();
        return function (Request $request, Response $response, callable $next) use ($container): Response {
            $flash = $container->flash;
            if ($flash->hasMessage('nonce')) {
                $nonceInFlash = $flash->getMessage('nonce');
                $nonceInRequest = $request->getParsedBodyParam('nonce');
                if (is_null($nonceInRequest)) {
                    $nonceInRequest = $request->getQueryParam('nonce');
                }
                if (is_null($nonceInRequest) || $nonceInRequest !== $nonceInFlash) {
                    return $response->withStatus(429); // 429: Too Many Requests
                }
            }
            return $next($request, $response);
        };
    }

    public static function ipRestriction(string $ipPattern) {
        $options = [];
        if ($ipPattern !== "*.*.*.*") $options["ip"] = $ipPattern;
        return new RestrictRoute($options);
    }

    public static function guard() {
        return Application::getInstance()->getApp()->getContainer()->get('guard');
    }
}