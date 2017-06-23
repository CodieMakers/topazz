<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin;


use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Admin\Controller\DashboardController;
use Topazz\Admin\Controller\FilesController;
use Topazz\Admin\Controller\ModuleController;
use Topazz\Admin\Controller\PageController;
use Topazz\Admin\Controller\PostController;
use Topazz\Admin\Controller\ProjectController;
use Topazz\Admin\Controller\SettingsController;
use Topazz\Admin\Controller\ThemeController;
use Topazz\Admin\Controller\UserController;
use Topazz\Admin\Middleware\Authorization;
use Topazz\Application;
use Topazz\Auth\AuthenticationMiddleware;
use Topazz\Container;
use Topazz\Middleware\SecurityMiddleware;
use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\Module;

class AdministrationModule extends Module {

    protected $name = "admin";
    protected $application;
    protected $config;
    protected $events;

    public function __construct(Container $container) {
        parent::__construct($container);
        $this->config = $container->config;
        $this->events = $container->events;
        $this->application = Application::getInstance()->getApp();
    }

    public function isNeeded(): bool {
        $adminHost = $this->config->get('admin.host');
        if ($adminHost === "system.default_host") {
            $adminHost = $this->config->get('system.default_host');
        }
        return $_SERVER['HTTP_HOST'] === $adminHost;
    }

    public function hasTemplates(): bool {
        return false;
    }

    public function hasConfig(): bool {
        return true;
    }

    public function getConfigFilename(): string {
        return "admin.yml";
    }

    public function run() {

        $this->events->emit("onBeforeAdminInit");

        $this->application->group($this->config->get('admin.uri'), function () {
            /** @var App $this */
            $this->get('', function (Request $request, Response $response) {
                return $response->withRedirect('/admin/');
            });
            $this->get('/', DashboardController::class . ":index");
            $this->get('/current-user', DashboardController::class . ':currentUser');
            $this->get('/dashboard', DashboardController::class . ':getData');
            $this->get('/nonce', DashboardController::class . ':nonce');
            $this->get('/csrf', DashboardController::class . ':csrfToken');

            // ------------ USERS
            $this->group('/users', function () {
                /** @var App $this */
                $this->get('', UserController::class . ':index');
                $this->get('/{id:[0-9]+}', UserController::class . ':detail');
                $this->put('', UserController::class . ':create');
                $this->post('/{id:[0-9]+}', UserController::class . ':update');
                $this->delete('/{id:[0-9]+}', UserController::class . ':remove');
            })->add(Authorization::withPermission('system.users'));

            // ------------ PROJECTS
            $this->group('/projects', function () {
                /** @var App $this */
                $this->get("", ProjectController::class . ':index');
                $this->get('/{id:[0-9]+}', ProjectController::class . ':detail');
                $this->put('', ProjectController::class . ':create');
                $this->post('/{id:[0-9]+}', ProjectController::class . ':update');
                $this->delete('/{id:[0-9]+}', ProjectController::class . ':remove');
            })->add(Authorization::withPermission('system.projects'));

            // ------------ PAGES
            $this->group('/pages', function () {
                /** @var App $this */
                $this->get("", PageController::class . ':index');
                $this->get('/{id:[0-9]+}', PageController::class . ':detail');
                $this->put('', PageController::class . ':create');
                $this->post('/{id:[0-9]+}', PageController::class . ':update');
                $this->delete('/{id:[0-9]+}', PageController::class . ':remove');
            })->add(Authorization::withPermission('system.pages'));

            // ------------ POSTS
            $this->group('/posts', function () {
                /** @var App $this */
                $this->get("", PostController::class . ':index');
                $this->get('/[{id:[0-9]+}]', PostController::class . ':detail');
                $this->put('', PostController::class . ':create');
                $this->post('/{id:[0-9]+}', PostController::class . ':update');
                $this->delete('/{id:[0-9]+}', PostController::class . ':remove');
            })->add(Authorization::withPermission('system.posts'));

            // ------------ MODULES
            $this->group('/modules', function () {
                /** @var App $this */
                $this->get('', ModuleController::class . ':index');
                $this->group('/detail/{moduleName}', function () {
                    /** @var App $this */
                    $this->get('', ModuleController::class . ':detail');
                    $this->post('', ModuleController::class . ':update');
                    $this->delete('', ModuleController::class . ':remove');
                });
                $this->group('/install', function () {
                    /** @var App $this */
                    $this->get('', ModuleController::class . ':installIndex');
                    $this->post('/{moduleName}', ModuleController::class . ':install');
                });
            })->add(Authorization::withPermission('system.modules'));

            // ------------ THEMES
            $this->group('/themes', function () {
                /** @var App $this */
                $this->get('', ThemeController::class . ':index');
                $this->group('/detail/{themeName}', function () {
                    /** @var App $this */
                    $this->get('', ThemeController::class . ':detail');
                    $this->post('', ThemeController::class . ':update');
                    $this->delete('', ThemeController::class . ':remove');
                });
                $this->group('/install', function () {
                    /** @var App $this */
                    $this->get('', ThemeController::class . ':installIndex');
                    $this->post('/{themeName}', ThemeController::class . ':install');
                });
            })->add(Authorization::withPermission('system.themes'));

            // ------------ SETTINGS
            $this->group('/settings', function () {
                /** @var App $this */
                $this->get('', SettingsController::class . ':index');
                $this->post('', SettingsController::class . ':update');
            })->add(Authorization::withPermission('system.settings'));

            // ------------ FILES
            $this->group('/files', function () {
                /** @var App $this */
                $this->get('', FilesController::class . ':index');
                $this->post('', FilesController::class . ':upload');
                $this->delete('/{name}', FilesController::class . ':remove');
            });
        })
            ->add(SecurityMiddleware::ipRestriction($this->config->get('admin.security.ip_restriction')))
            ->add($this->container->security->guard())
            ->add(AuthenticationMiddleware::withRedirect('/admin/'));

        $this->events->emit("onAfterAdminInit");
        $this->events->emit("onAdminInit");
    }
}