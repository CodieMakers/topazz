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
use Topazz\Controller\Controller;
use Topazz\Data\Collection;
use Topazz\Data\RandomStringGenerator;
use Topazz\Database\Connector;
use Topazz\Database\Database;
use Topazz\Entity\User;
use Topazz\Environment;

class InstallController extends Controller {

    protected $recommendedPlugins = [];

    public function welcome(Request $request, Response $response) {
        return $this->renderer->render($request, $response, "@installer/hello.twig");
    }

    public function showInstall(Request $request, Response $response): ResponseInterface {
        return $this->renderer->render($request, $response, "@installer/index.twig");
    }

    public function install(Request $request, Response $response): ResponseInterface {
        $collection = new Collection();
        $collection->putAll(is_array($request->getParsedBody()) ? $request->getParsedBody() : []);

        if ($collection->hasAllKeys(["db-name", "db-user", "db-password"])) {
            $this->installDatabase(
                $collection->find('db-name')->orNull(),
                $collection->find("db-user")->orNull(),
                $collection->find("db-password")->orNull()
            );

            $redirect = $this->container->router->pathFor('installer.success');
            if (!empty($this->recommendedPlugins)) { // TODO: add recommended plugins
                $redirect = $this->container->router->pathFor('installer.plugins');
            }
            return $response->withStatus(200)->withHeader('Location', $redirect);
        }
        return $this->renderer->render($request, $response, "@installer/index.twig");
    }

    private function installDatabase($dbName, $dbUser, $dbPassword) {
        $generatedPassword = RandomStringGenerator::generate(30);

        Environment::set('DB_NAME', $dbName);
        Environment::set('DB_USER', "topazz_db");
        Environment::set("DB_PASSWORD", $generatedPassword);

        Connector::setUser($dbUser, $dbPassword);

        Connector::connect()->executeAll(new Collection([
            Database::custom("DROP SCHEMA IF EXISTS ?")->values([$dbName]),
            Database::custom("CREATE SCHEMA IF NOT EXISTS ?")->values([$dbName]),
            Database::custom("DROP USER IF EXISTS 'topazz_db'"),
            Database::custom("CREATE USER IF NOT EXISTS 'topazz_db' IDENTIFIED BY '{$generatedPassword}'"),
            Database::custom("USE ?")->values([$dbName])
        ]));
        // TODO: drop and create tables
    }

    private function installAdminModule($adminUsername, $adminPassword) {
        $adminUser = new User();
        $adminUser->role = User::ROLE_ADMIN;
        $adminUser->username = $adminUsername;
        $adminUser->setPassword($adminPassword);
        $adminUser->create();

        // TODO: insert into modules table

    }

    public function showInstallPlugins(Request $request, Response $response): ResponseInterface {
        return $this->renderer->render($request, $response,
            "@installer/plugins.twig", ["plugins" => $this->recommendedPlugins]);
    }

    public function installPlugins(Request $request, Response $response): ResponseInterface {
        // TODO: install plugins
        return $response->withStatus(200)
            ->withHeader('Location', $this->container->router->pathFor('installer.success'));
    }

    public function success(Request $request, Response $response): ResponseInterface {
        return $this->renderer->render($request, $response, "@installer/success.twig");
    }
}