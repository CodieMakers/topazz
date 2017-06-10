<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

class PageController extends Controller {

    public function index(Request $request, Response $response) {
        return $response;
    }
}