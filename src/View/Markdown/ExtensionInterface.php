<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Markdown;


interface ExtensionInterface {

    public function filter(string $markdown): string;

    public function prepend(): string;

    public function append(): string;
}