<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Auth;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Controller\Controller;
use Topazz\Data\Filesystem;
use Topazz\Data\Optional;
use Topazz\Entity\User;

class AuthController extends Controller {

    public function index(Request $request, Response $response) {
        return $this->renderer->display($request, $response, "@auth/login.twig");
    }

    public function login(Request $request, Response $response) {
        $uri = $request->getQueryParam("return_uri");
        /** @var Optional $user */
        $user = User::find("username", $request->getParsedBodyParam("username"))->first();
        if ($user->isNull()) {
            return $this->renderer->display($request, $response, "@auth/login.twig", [
                "error" => "This user was not found. Please check this form and try again.",
                "username" => $request->getParsedBodyParam("username"),
                "remember-me" => $request->getParsedBodyParam("remember-me")
            ]);
        }
        $user = $user->orNull(); //there cannot be null
        /** @var User $user */
        if (!$user->matchPassword($request->getParsedBodyParam("password"))) {
            return $this->renderer->display($request, $response, "@auth/login.twig", [
                "error" => "There is no user matching this combination of username and password.",
                "show_lost_password" => true,
                "username" => $request->getParsedBodyParam("username"),
                "remember-me" => $request->getParsedBodyParam("remember-me")
            ]);
        }
        if ($request->getParsedBodyParam("remember-me") == "on") {
            $_SESSION["user_id"] = $user->uuid;
        } else {
            $cookie = "user_id=" . $user->uuid . "; path=/; domain=" . $_SERVER["HTTP_HOST"];
            $response = $response->withAddedHeader("Set-Cookie", $cookie);
        }
        return $response->withHeader("Location", isset($uri) ? $uri : '/admin');
    }

    public function logout(Request $request, Response $response): Response {
        if (isset($_SESSION["user_id"])) {
            unset($_SESSION["user_id"]);
        } else {
            $cookie = "user_id=invalid; expires=" . (new \DateTime("now - 1 year"))->format("D, d M Y H:i:s") . " GTM; path=/; domain=" . $_SERVER["HTTP_HOST"];
            $response = $response->withAddedHeader("Set-Cookie", $cookie);
        }
        return $response->withStatus(200)->withRedirect("/");
    }
}