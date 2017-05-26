<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Composer\Console\Application;
use Topazz\Module\Console\Input;
use Topazz\Module\Console\Output;

class ModuleInstaller {

    private $composerApplication;

    public function __construct() {
        $this->composerApplication = new Application();
        $this->composerApplication->setAutoExit(false);
    }

    /**
     * @return \Topazz\Data\Collection
     */
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
        return $output->getMessages()->filter(function ($message) {
            return preg_match('/([\w]+\/[\w]+)\s(\w)?/', $message);
        })->map(function ($message) {
            $packageName = explode(' ', $message)[0];
            return [
                "package" => $packageName,
                "description" => str_replace($packageName . ' ', '', $message)
            ];
        });
    }

    /**
     * @param string $packageName
     *
     * @return string
     */
    public function install(string $packageName) {
        $input = new Input("require", ["packages" => [$packageName], "--no-progress", "--prefer-stable"]);
        $output = new Output();
        $this->composerApplication->run($input, $output);
        return $output->join();
    }
}