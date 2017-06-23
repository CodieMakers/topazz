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
use Topazz\Entity\EntityNotFoundException;
use Topazz\Entity\Page;

class PageController extends Controller implements ContentControllerInterface {

    public function index(Request $request, Response $response): Response {
        return $response->withJson(Page::all()->toArray());
    }

    public function detail(Request $request, Response $response, array $args): Response {
        /** @var Page $page */
        $page = Page::find('id', $args['id'])->first()->orNull();
        return $response->withJson($page);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        try {
            /** @var Page $page */
            $page = Page::findById($args['id']);
            foreach (get_object_vars($page) as $var => $value) {
                if (isset($data[$var])) {
                    $page->$var = $data[$var];
                }
            }
            $page->save();
            return $response->withJson($page);
        } catch (EntityNotFoundException $e) {
            return $response->withStatus(404);
        }
    }

    public function remove(Request $request, Response $response, array $args): Response {
        if (Page::remove($args['id'])) {
            return $response->withStatus(200);
        }
        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $page = new Page();
        foreach (get_object_vars($page) as $var => $value) {
            if (isset($data[$var])) {
                $page->$var = $data[$var];
            }
        }
        $page->save();
        return $response->withJson($page);
    }
}