<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;

use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Controller\Controller;
use Topazz\Entity\EntityNotFoundException;
use Topazz\Entity\User;

class UserController extends Controller implements ContentControllerInterface {

    public function index(Request $request, Response $response): Response {
        $users = User::all();
        return $response->withJson($users->toArray());
    }

    public function detail(Request $request, Response $response, array $args): Response {
        try {
            $user = User::findById($args["id"]);
        } catch (\Exception $exception) {
            return $response->withStatus(404);
        }
        return $response->withJson($user);
    }

    public function create(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        if (!(Validator::key('username')->validate($data) && Validator::notEmpty()->validate($data["username"]) &&
            Validator::key('email')->validate($data) && Validator::email()->validate($data["email"]) &&
            Validator::key('password')->validate($data) && Validator::notEmpty()->validate($data["username"]))
        ) {
            return $response->withStatus(400)->write('Missing some required parameters.');
        }
        $user = new User();
        $user->username = $data["username"];
        $user->email = $data["email"];
        $user->setPassword($data['password']);
        if (Validator::key('first_name')->validate($data)) {
            $user->first_name = $data["first_name"];
        }
        if (Validator::key('last_name')->validate($data)) {
            $user->last_name = $data["last_name"];
        }
        if (Validator::key('profile_picture')->validate($data)) {
            $user->profile_picture = $data["profile_picture"];
        }
        if (Validator::key('role')->validate($data) &&
            Validator::numeric()->between(0, 3)->validate($data["role"])
        ) {
            $user->role = $data["role"];
        }
        $user->save();
        return $response->withStatus(200)->withJson($user);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        try {
            /** @var User $user */
            $user = User::findById($args['id']);
            if (Validator::key('username')->validate($data)) {
                $user->username = $data["username"];
            }
            if (Validator::key('email')->validate($data)) {
                $user->email = $data["email"];
            }
            if (Validator::key('password')->validate($data)) {
                $user->setPassword($data["password"]);
            }
            if (Validator::key('first_name')->validate($data)) {
                $user->first_name = $data["first_name"];
            }
            if (Validator::key('last_name')->validate($data)) {
                $user->last_name = $data["last_name"];
            }
            if (Validator::key('profile_picture')->validate($data)) {
                $user->profile_picture = $data["profile_picture"];
            }
            if (Validator::key('role')->validate($data)) {
                $user->role = (int)$data["role"];
            }
            $user->save();
            return $response->withJson($user);
        } catch (EntityNotFoundException $exception) {
            return $response->withStatus(404);
        }
    }

    public function remove(Request $request, Response $response, array $args): Response {
        if (User::remove($args['id'])) {
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }
}