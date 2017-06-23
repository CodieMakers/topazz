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
use Topazz\Entity\Post;

class PostController extends Controller implements ContentControllerInterface {

    public function index(Request $request, Response $response): Response {
        return $response->withJson(Post::all()->toArray());
    }

    public function detail(Request $request, Response $response, array $args): Response {
        return $response->withJson(Post::find('id', $args['id'])->first()->orNull());
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        try {
            /** @var Post $post */
            $post = Post::findById($args['id']);
            foreach (get_object_vars($post) as $var => $value) {
                if (isset($data[$var])) {
                    $post->$var = $data[$var];
                }
            }
            $post->save();
            return $response->withJson($post);
        } catch (EntityNotFoundException $e) {
            return $response->withStatus(404);
        }
    }

    public function remove(Request $request, Response $response, array $args): Response {
        if (Post::remove($args['id'])) {
            return $response->withStatus(200);
        }
        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $post = new Post();
        foreach (get_object_vars($post) as $var => $value) {
            if (isset($data[$var])) {
                $post->$var = $data[$var];
            }
        }
        $post->save();
        return $response->withJson($post);
    }
}