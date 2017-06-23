<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Controller\Controller;
use Topazz\Data\Filesystem;

class DashboardController extends Controller {

    public function index(Request $request, Response $response) {
        return $response->write(Filesystem::fromPath()->read("public/admin.html"));
    }

    public function currentUser(Request $request, Response $response) {
        return $response->withJson($request->getAttribute('current_user'));
    }

    public function nonce(Request $request, Response $response): Response {
        return $response->write($this->container->security->nonce());
    }

    public function csrfToken(Request $request, Response $response): Response {
        return $response->withJson($this->container->security->getCsrfToken());
    }

    public function getData(Request $request, Response $response) {

    }
}