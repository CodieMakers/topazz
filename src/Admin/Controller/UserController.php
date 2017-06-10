<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\ApplicationException;
use Topazz\Controller\Controller;
use Topazz\Entity\User;

class UserController extends Controller {

    public function index(Request $request, Response $response) {
        $users = User::all();
        return $this->renderer->withRequest($request)->render($response, "admin/user/index.twig", ["users" => $users]);
    }

    public function detail(Request $request, Response $response, array $args): ResponseInterface {
        try {
            $user = User::findById($args["id"])->orThrow(new ApplicationException("We could NOT find this user!"));
        } catch (\Exception $exception) {
            return $response->withStatus(404);
        }
        return $this->renderer->withRequest($request)->render($response, "admin/user/detail.twig", ["user" => $user]);
    }

    public function saveDetail(Request $request, Response $response, array $args): ResponseInterface {
        return $response;
    }
}