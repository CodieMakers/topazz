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

class ThemeController extends Controller {

    public function index(Request $request, Response $response): Response {
        $data = [];
        $themes = $this->config->get('themes.installed')->toArray(true);
        foreach ($themes as $themeName => $themeConfig) {
            $this->container->themes->loadTheme($themeName);
            $theme = $this->container->themes->findTheme($themeName);
            $data[] = $theme;
        }
        return $response->withJson($themes);
    }

    public function detail(Request $request, Response $response, array $args): Response {
        $this->container->themes->loadTheme($args['themeName']);
        $theme = $this->container->themes->findTheme($args['themeName']);
        return $response->withJson($theme);
    }

    public function enable() {}

    public function disable() {}

    public function installable() {}
}