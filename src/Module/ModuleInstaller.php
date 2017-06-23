<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Composer\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Yaml\Yaml;
use Topazz\Config\Config;
use Topazz\Container;
use Topazz\Data\Collections\ListInterface;
use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Data\Filesystem;
use Topazz\Module\Console\Input;
use Topazz\Module\Console\Output;

class ModuleInstaller {

    private $composerApplication;
    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->composerApplication = new Application();
        $this->composerApplication->setAutoExit(false);
    }

    public function listModules() {
        return $this->listAll("modules");
    }

    public function listThemes() {
        return $this->listAll("themes");
    }

    protected function listAll(string $type): ListInterface {
        $config = $this->container->config;
        $fileContent = file_get_contents("https://raw.githubusercontent.com/CodieMakers/topazz-modules-registry/master/{$type}.yml");
        if (is_bool($fileContent) && !$fileContent) {
            return new ArrayList();
        }
        $list = Yaml::parse($fileContent);
        $config->set("{$type}.available", new Config($list));
        return new ArrayList($list);
    }

    public function install(string $packageName) {
        $input = new Input("require", ["packages" => [$packageName], "--no-progress", "--prefer-stable"]);
        $output = new Output();
        $this->composerApplication->run($input, $output);
        return $output->join();
    }
}