<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module;


use Topazz\View\Twig;

abstract class ModuleWithTemplates extends Module {

    /** @var Twig $view */
    protected $view;

    protected $templateDir;

    public function __construct() {
        parent::__construct();
        $this->view = $this->container->get('view');
        $this->templateDir = "modules/" . $this->name . "/templates";
    }

    /**
     * @return string
     */
    public function getTemplateDir(): string {
        return $this->templateDir;
    }

    /**
     * @inheritDoc
     */
    public function setup() {
        $this->view->registerModuleTemplatesDir($this->templateDir, $this->name);
    }


}