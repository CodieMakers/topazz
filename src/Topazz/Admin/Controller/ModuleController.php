<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Container;
use Topazz\Controller\Controller;
use Topazz\Module\ModuleInstaller;

class ModuleController extends Controller {

    protected $moduleInstaller;

    public function __construct(Container $container) {
        parent::__construct($container);
        $this->moduleInstaller = new ModuleInstaller();
    }

    public function modules(Request $request, Response $response) {
        $modules = $this->moduleInstaller->listModules();
        return $this->view->withRequest($request)->render($response, "@admin/modules/index.twig", [
            "modules" => $modules
        ]);
    }
}