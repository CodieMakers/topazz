<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Topazz\Container;
use Topazz\View\Markdown\ExtensionInterface;

class Markdown {

    protected $parsedown;
    protected $extensions = [];

    public function __construct(Container $container) {
        $this->parsedown = new \ParsedownExtra();
    }

    public function render(string $markdown): string {
        $prepend = "";
        $append = "";
        /** @var ExtensionInterface $extension */
        foreach ($this->extensions as $extension) {
            $prepend .= $extension->prepend();
            $markdown = $extension->filter($markdown);
            $append .= $extension->append();
        }
        return $prepend . $this->parsedown->parse($markdown) . $append;
    }

    public function addExtension(ExtensionInterface $extension) {
        $this->extensions[] = $extension;
    }
}