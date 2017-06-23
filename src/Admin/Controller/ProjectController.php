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
use Topazz\Entity\Project;

class ProjectController extends Controller implements ContentControllerInterface {

    public function index(Request $request, Response $response): Response {
        return $response->withJson(Project::all()->toArray());
    }

    public function detail(Request $request, Response $response, array $args): Response {
        return $response->withJson(Project::find('id', $args['id'])->first()->orNull());
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        try {
            /** @var Project $project */
            $project = Project::findById($args['id']);
            foreach (get_object_vars($project) as $var => $value) {
                if (isset($data[$var])) {
                    $project->$var = $data[$var];
                }
            }
            $project->save();
            return $response->withJson($project);
        } catch (EntityNotFoundException $e) {
            return $response->withStatus(404);
        }
    }

    public function remove(Request $request, Response $response, array $args): Response {
        if (Project::remove($args['id'])) {
            return $response->withStatus(200);
        }
        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $project = new Project();
        foreach (get_object_vars($project) as $var => $value) {
            if (isset($data[$var])) {
                $project->$var = $data[$var];
            }
        }
        $project->save();
        return $response->withJson($project);
    }
}