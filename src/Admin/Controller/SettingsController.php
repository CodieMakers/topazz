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

class SettingsController extends Controller {

    public function index(Request $request, Response $response) {
        return $response->withJson([
            "system" => $this->config->get('system')->toArray(true),
            "user.available_permissions" => (array)$this->config->get('user.available_permissions'),
            "user.permissions" => $this->config->get('user.permissions', [])
        ]);
    }

    public function update(Request $request, Response $response) {
        $data = $request->getParsedBody();
        foreach ($data as $key => $value) {
            $this->config->set($key, $value);
        }
        return $response->withStatus(200);
    }
}