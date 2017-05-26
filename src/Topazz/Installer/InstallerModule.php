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
use Symfony\Component\Filesystem\Filesystem;
use Topazz\Admin\Administration;
use Topazz\Application;
use Topazz\Data\Collection;
use Topazz\Data\RandomStringGenerator;
use Topazz\Database\Connector;
use Topazz\Database\MultiQuery;
use Topazz\Database\Query;
use Topazz\Entity\Page;
use Topazz\Entity\Post;
use Topazz\Entity\Project;
use Topazz\Entity\User;
use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\Module;
use Topazz\Module\ModuleWithTemplates;
use Topazz\Theme\Theme;

class InstallerModule extends ModuleWithTemplates {

    public $name = "installer";
    public $activated = true;
    public $templateDir = "templates/installer";
    public $recommendedPlugins = [];

    public function welcome(Request $request, Response $response) {
        return $this->view->render($request, $response, "@installer/hello.twig");
    }

    public function showInstaller(Request $request, Response $response): ResponseInterface {
        return $this->view->render($request, $response, "@installer/index.twig");
    }

    public function install(Request $request, Response $response): ResponseInterface {
        $parsedBody = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
        $collection = (new Collection($parsedBody))->filterEmpty();
        if ($collection->hasAllKeys(["db-name", "db-user", "db-password", "admin-username", "admin-password"])) {
            $adminHost = $request->getUri()->getHost();
            $adminUri = "/admin";
            if (isset($parsedBody['admin-base']) && !empty($parsedBody['admin-base'])) {
                $adminHost = $parsedBody['admin-base'];
                $adminUri = $parsedBody['admin-uri'];
            }

            $this->installEnvFile(
                isset($parsedBody["db-host"]) && !empty($parsedBody['db-host']) ?
                    $parsedBody['db-host'] : 'localhost',
                $parsedBody['db-name'],
                $adminHost,
                $adminUri,
                isset($parsedBody['admin-ip-restriction']) && !empty($parsedBody['admin-ip-restriction']) ?
                    $parsedBody['admin-ip-restriction'] : null
            );
            $this->installDatabase($parsedBody['db-user'], $parsedBody['db-password']);
            $this->installAdminModule($parsedBody['admin-username'], $parsedBody['admin-password']);

            $redirect = $this->container->router->pathFor('installer.success');
            if (!empty($this->recommendedPlugins)) { // TODO: add recommended plugins
                $redirect = $this->container->router->pathFor('installer.plugins');
            }
            return $response->withStatus(200)->withHeader('Location', $redirect);
        }
        $this->container->flash()->addMessageNow('error', 'You have to fill in all required inputs');
        return $this->view->render($request, $response, "@installer/index.twig");
    }

    public function showRecommendedPlugins(Request $request, Response $response): ResponseInterface {
        return $this->view->render($request, $response,
            "@installer/plugins.twig", ["plugins" => $this->recommendedPlugins]);
    }

    public function installPlugins(Request $request, Response $response): ResponseInterface {
        // TODO: install plugins
        return $response->withStatus(200)->withHeader('Location', $this->container->router->pathFor('installer.success'));
    }

    public function success(Request $request, Response $response): ResponseInterface {
        return $this->view->render($request, $response, "@installer/success.twig");
    }

    /**
     * @inheritdoc
     */
    public function isEnabled() {
        return getenv("ENV") === "installation";
    }

    /**
     * @inheritdoc
     */
    public function setup() {
        parent::setup();
        $this->router->get('/', [$this, 'welcome']);

        $this->router->get('/install', [$this, "showInstaller"])->setName("installer.index");
        $this->router->post("/install", [$this, "install"]);

        $this->router->get('/install-plugins', [$this, "showRecommendedPlugins"])->setName('installer.plugins');
        $this->router->post('/install-plugins', [$this, "installPlugins"]);

        $this->router->get('/install-success', [$this, "success"])->setName('installer.success');

        $this->router->add(TemplateConfigMiddleware::withBodyClass('installer'));
        $this->router->add(TemplateConfigMiddleware::withPageTitle('Topazz system installation'));
    }

    private function installEnvFile($dbHost, $dbName, $adminHost, $adminUri, string $ipRestriction = null) {
        $fileContent =
            //"ENV=production" . PHP_EOL .
            "DB_HOST=" . $dbHost . PHP_EOL .
            "DB_NAME=" . $dbName . PHP_EOL .
            "DB_USER=topazz_db" . PHP_EOL .
            "DB_PASSWORD=" . RandomStringGenerator::generate(10) . PHP_EOL .
            "SECRET=" . RandomStringGenerator::generate(20) . PHP_EOL .
            "ADMIN_HOST=" . $adminHost . PHP_EOL .
            "ADMIN_URI=" . $adminUri . PHP_EOL .
            (!is_null($ipRestriction) ? ("ADMIN_IP_RESTRICTION=" . $ipRestriction . PHP_EOL) : "") .
            "ENV=installation";
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists(".env")) {
            $fileSystem->touch(".env");
        }
        $fileSystem->dumpFile(".env", $fileContent);
        Application::getInstance()->getEnvironmentLoader()->load(".env");
    }

    private function installAdminModule($adminUsername, $adminPassword) {
        $adminUser = new User();
        $adminUser->role = User::ROLE_ADMIN;
        $adminUser->username = $adminUsername;
        $adminUser->setPassword($adminPassword);
        $adminUser->save();

        // ... insert into modules table
        /** @var Connector $db */
        $db = Application::getInstance()->getContainer()->get('db');
        $db->query(Query::create(/** @lang MySQL */
            "INSERT INTO modules (name, module_class, activated) VALUES (?, ?, ?)"
        )->setAttributes(["admin", Administration::class, true]))->run();
    }

    private function installDatabase($dbUser, $dbPassword) {
        $schema = getenv('DB_NAME');
        $password = getenv('DB_PASSWORD');
        $query = new MultiQuery();
        $query
            ->addQuery(/** @lang MySQL */
                "DROP SCHEMA IF EXISTS `{$schema}`")
            ->addQuery(/** @lang MySQL */
                "CREATE SCHEMA IF NOT EXISTS `{$schema}` DEFAULT CHARACTER SET utf8")
            ->addQuery(/** @lang MySQL */
                "CREATE USER IF NOT EXISTS 'topazz_db' IDENTIFIED BY '{$password}'")
            ->addQuery(/** @lang MySQL */
                "USE `{$schema}`")
            ->addQuery(User::getTable()->getCreateTableQuery())
            ->addQuery(Project::getTable()->getCreateTableQuery())
            ->addQuery(Page::getTable()->getCreateTableQuery())
            ->addQuery(Post::getTable()->getCreateTableQuery())
            ->addQuery(Module::getTable()->getCreateTableQuery())
            ->addQuery(Theme::getTable()->getCreateTableQuery())
            ->addQuery(/** @lang MySQL */
                "GRANT SELECT, INSERT, UPDATE, DELETE, TRIGGER ON `{$schema}`.* TO 'topazz_db'");
        /** @var Connector $db */
        $db = Application::getInstance()->getContainer()->get('db');
        $db->setUser($dbUser, $dbPassword)->query($query)->run()->resetUser();
    }
}