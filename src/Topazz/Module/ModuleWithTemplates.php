<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\View\Renderer;

abstract class ModuleWithTemplates extends Module {

    /** @var Renderer $view */
    protected $view;

    protected $templateDir;

    public function __construct() {
        parent::__construct();
        $this->view = $this->container->renderer();
    }

    /**
     * @return string
     */
    public function getTemplateDir(): string {
        return is_null($this->templateDir) ? "modules/" . $this->name . "/templates" : $this->templateDir;
    }

    /**
     * @inheritDoc
     */
    public function setup() {
        $this->container->get('view')->registerModuleTemplatesDir(static::getTemplateDir(), $this->name);
    }
}