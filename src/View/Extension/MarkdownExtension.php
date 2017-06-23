<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use ParsedownExtra;
use Topazz\Container;

class MarkdownExtension extends \Twig_Extension {

    protected $parsedown;

    public function __construct(Container $container) {
        $this->parsedown = $container->markdown;
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter("markdown", [$this, "parseMarkdown"], ["is_safe" => ["html"]]),
            new \Twig_SimpleFilter("md", [$this, "parseMarkdown"], ["is_safe" => ["html"]])
        ];
    }

    public function parseMarkdown(string $source) {
        return $this->parsedown->render($source);
    }
}