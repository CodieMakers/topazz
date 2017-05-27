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

class DashboardController extends Controller {

    public function index(Request $request, Response $response) {
        return $this->renderer->render($request, $response, '@admin/index.twig');
    }
}