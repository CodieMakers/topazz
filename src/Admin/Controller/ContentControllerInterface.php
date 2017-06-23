<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

interface ContentControllerInterface {

    public function index(Request $request, Response $response): Response;
    public function detail(Request $request, Response $response, array $args): Response;
    public function create(Request $request, Response $response): Response;
    public function update(Request $request, Response $response, array $args): Response;
    public function remove(Request $request, Response $response, array $args): Response;
}