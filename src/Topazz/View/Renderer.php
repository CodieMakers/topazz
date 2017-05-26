<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Container;

class Renderer {

    protected $view;

    public function __construct(Container $container) {
        $this->view = $container->get('view');
    }

    public function render(Request $request, Response $response, string $template, $data = []) {
        return $this->view->withRequest($request)->render($response, $template, $data);
    }
}