<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


class Breadcrumbs {

    protected $levels = [];

    public function add(string $label, string $uri) {
        $this->levels []= ["uri" => $uri, "label" => $label];
    }

    public function __toString(): string {
        $prepend = "<span class='breadcrumbs'>";
        $append = "</span>";
        $breadcrumbs = [];
        foreach ($this->levels as $level) {
            $breadcrumbs []= "<a href='{$level['uri']}' class='charcoal-light mx1'>{$level['label']}</a>";
        }
        return $prepend . join('<i class="fa fa-angle-right"></i>', $breadcrumbs) . $append;
    }
}