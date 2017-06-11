<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Tests\Module;

use PHPUnit\Framework\TestCase;
use Topazz\Application;
use Topazz\Module\ModuleInstaller;

class ModuleInstallerTest extends TestCase {

    /** @var ModuleInstaller $moduleInstaller */
    private $moduleInstaller;

    protected function setUp() {
        $this->moduleInstaller = Application::getInstance()->getApp()->getContainer()->get('modules')->installer();
    }

    public function testListModules() {
        $modules = $this->moduleInstaller->listModules();
        var_dump($modules);
        $this->assertNotNull($modules);
    }

    public function testInstallModule() {
        $this->moduleInstaller->install("topazz/simple");
    }
}
