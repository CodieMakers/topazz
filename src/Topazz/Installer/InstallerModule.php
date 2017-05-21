<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Installer;


use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Module\ModuleWithTemplates;

class InstallerModule extends ModuleWithTemplates {

    public $name = "installer";
    public $activated = true;

    public function install(Request $request, Response $response): ResponseInterface {
        if ($request->isGet()) {
            return $this->view->withRequest($request)->render($response, "installer/index.twig");
        }
        if (!in_array("", $request->getParsedBody())) {
            return $this->view->withRequest($request)->render($response, "installer/index.twig", [
                "error" => true
            ]);
        }
        return $response;
    }

    /**
     * @return boolean Returns TRUE if this module is enabled, otherwise FALSE.
     */
    public function isEnabled() {
        $settings = Application::getInstance()->getContainer()->get('settings');
        return is_null($settings->get('installed_version'));
    }

    /**
     * @return void
     */
    public function setup() {
        $this->router->get('/', function (Request $request, Response $response) {
            return $response->withRedirect('/install');
        });

        $this->router->any('/install', [$this, "install"]);
    }
}