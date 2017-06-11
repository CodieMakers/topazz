<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Topazz\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Controller\Controller;
use Topazz\Module\Module;
use Topazz\Module\ModuleInstaller;

class ModuleController extends Controller {

    protected $moduleInstaller;

    public function __construct(Container $container) {
        parent::__construct($container);
        $this->moduleInstaller = new ModuleInstaller();
    }

    public function modules(Request $request, Response $response) {
        $modules = Module::all();
        return $this->renderer->render($request, $response, "@admin/modules/index.twig", [
            "modules" => $modules
        ]);
    }
}