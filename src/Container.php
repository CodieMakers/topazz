<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;


use Slim\Container as SlimContainer;

class Container extends SlimContainer {

    public function __construct($settings = []) {
        parent::__construct(["settings" => $settings]);
    }

    public function registerService(string $serviceKey, string $serviceClass) {
        $this[$serviceKey] = function ($c) use ($serviceClass) {
            return new $serviceClass($c);
        };
    }
}