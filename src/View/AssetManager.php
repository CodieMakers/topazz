<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


class AssetManager {

    protected $css = [];
    protected $js = [];

    public function addCss(string $path) {
        $this->css[] = $path;
    }

    public function addJs(string $path) {
        $this->js[] = $path;
    }

    public function css() {
        $cssLinks = "";
        foreach ($this->css as $css) {
            $cssLinks .= "<link rel=\"stylesheet\" href=\"$css\">";
        }
        return $cssLinks;
    }

    public function js($async = false) {
        $jsScripts = "";
        $async = $async? " async" : "";
        foreach ($this->js as $js) {
            $jsScripts .= "<script$async type=\"application/javascript\" src=\"$js\"></script>";
        }
        return $jsScripts;
    }
}