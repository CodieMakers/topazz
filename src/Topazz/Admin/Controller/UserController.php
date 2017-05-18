<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Topazz\Controller\Controller;

class UserController extends Controller {

    /**
     * Login form
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response) {

        return $response;
    }
}