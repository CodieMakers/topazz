<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use ParsedownExtra;

class ParsedownExtension extends \Twig_Extension {

    protected $parsedown;

    public function __construct() {
        $this->parsedown = new ParsedownExtra();
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter("markdown", [$this, "parseMarkdown"]),
            new \Twig_SimpleFilter("md", [$this, "parseMarkdown"])
        ];
    }

    public function parseMarkdown(string $source) {
        return $this->parsedown->parse($source);
    }
}