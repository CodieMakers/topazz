<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Markdown;


abstract class Extension implements ExtensionInterface {

    public function filter(string $markdown): string {
        return $markdown;
    }

    public function prepend(): string {
        return "";
    }

    public function append(): string {
        return "";
    }
}