<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Installer;


use Topazz\Middleware\TemplateConfigMiddleware;
use Topazz\Module\Module;

class InstallerModule extends Module {

    public $name = "installer";
    public $activated = true;
    public $templateDir = "templates/installer";
    public $recommendedPlugins = [];

    /**
     * @inheritdoc
     */
    public function isEnabled() {
        return \Topazz\Environment::get("ENV") === "installation";
    }

    /**
     * @inheritdoc
     */
    public function setup() {
        $this->application->get('/', InstallController::class . ':welcome');

        $this->application->get('/install', InstallController::class . ':showInstall')->setName("installer.index");
        $this->application->post("/install", InstallController::class . ':install');

        $this->application->get('/install-admin', InstallController::class . ':showInstallAdmin')->setName('installer.admin');
        $this->application->post('/install-admin', InstallController::class . ':installAdmin');

        $this->application->get('/install-plugins', InstallController::class . ':showInstallPlugins')->setName('installer.plugins');
        $this->application->post('/install-plugins', InstallController::class . ':installPlugins');

        $this->application->get('/install-success', InstallController::class . ':success')->setName('installer.success');

        $this->application->add(TemplateConfigMiddleware::withBodyClass('installer'));
        $this->application->add(TemplateConfigMiddleware::withPageTitle('Topazz system installation'));
    }

    public function create() {
        // TODO: Implement create() method.
    }

    public function update() {
        // TODO: Implement update() method.
    }

    public function remove() {
        // TODO: Implement remove() method.
    }
}