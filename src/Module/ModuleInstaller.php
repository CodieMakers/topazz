<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Composer\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Topazz\Container;
use Topazz\Module\Console\Input;
use Topazz\Module\Console\Output;

class ModuleInstaller {

    private $composerApplication;

    public function __construct(Container $container) {
        $this->composerApplication = new Application();
        $this->composerApplication->setAutoExit(false);
    }

    public function listModules() {
        return $this->listAll("topazz-module");
    }

    public function listThemes() {
        return $this->listAll("topazz-theme");
    }

    protected function listAll($type) {
        $input = new Input("search", ["--type" => $type, "tokens" => []]);
        $output = new Output();
        $this->composerApplication->run($input, $output);
        return $output->getMessages()->stream()->filter(function ($message) {
            return preg_match('/([\w-]+\/[\w-]+)\s(.*)?/', $message);
        })->map(function ($message) {
            $packageName = explode(' ', $message)[0];
            return [
                "package" => $packageName,
                "description" => str_replace($packageName . ' ', '', $message)
            ];
        });
    }

    public function install(string $packageName) {
        $input = new Input("require", ["packages" => [$packageName], "--no-progress", "--prefer-stable"]);
        $output = new Output();
        $this->composerApplication->run($input, $output);
        var_dump($output->join());
        // TODO: insert into config
    }

    public function infoAboutPackage(string $packageName) {
        $input = new Input("info", ["package" => $packageName, "--all"]);
        $output = new BufferedOutput();
        $this->composerApplication->run($input, $output);
        return $output->fetch();
    }
}