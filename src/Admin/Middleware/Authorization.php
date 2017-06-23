<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Entity\User;

class Authorization {

    public static function withRole(int $role) {
        return function (Request $request, Response $response, callable $next) use ($role) {
            /** @var User $user */
            $user = $request->getAttribute('current_user');
            if ($user->role === $role) {
                return $next($request, $response);
            }
            return $response->withStatus(403);
        };
    }

    public static function withPermission(string $permissionCode) {
        return function (Request $request, Response $response, callable $next) use ($permissionCode) {
            /** @var User $user */
            $user = $request->getAttribute('current_user');
            if ($user->hasPermission($permissionCode)) {
                return $next($request, $response);
            }
            return $response->withStatus(403);
        };
    }
}