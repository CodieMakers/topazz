<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\Container;

abstract class Module {

    protected $name;
    /** @var Container $container */
    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function getName() {
        return $this->name;
    }

    abstract public function isNeeded(): bool;

    abstract public function hasTemplates(): bool;

    public function getTemplateDir(): string {
        return '';
    }

    abstract public function hasConfig(): bool;

    public function getConfigFilename(): string {
        return '';
    }

    abstract public function run();
}