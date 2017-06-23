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
use Topazz\Container;
use Topazz\Controller\Controller;
use Topazz\Data\Collections\Lists\ArrayList;

class ModuleController extends Controller {

    protected $moduleManager;

    public function __construct(Container $container) {
        parent::__construct($container);
        $this->moduleManager = $container->modules;
    }

    public function index(Request $request, Response $response): Response {
        $modules = new ArrayList();
        foreach ($this->moduleManager->all() as $module) {

        }
        return $response->withJson($modules->toArray());
    }

    public function remove(Request $request, Response $response, array $args): Response {

    }

    public function enable(Request $request, Response $response): Response {

    }

    public function disable(Request $request, Response $response): Response {

    }

    public function installable(Request $request, Response $response): Response {
        $installableModules = $this->moduleManager->installer()->listModules()->toArray();
        return $response->withJson($installableModules);
    }

    public function install(Request $request, Response $response): Response {

    }
}