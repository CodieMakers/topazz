<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View;


use Slim\Http\Request;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;

class TwigGlobalsExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface {

    private $globals = [];
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    private function prepareGlobals() {
        $parsedBody = $this->request->getParsedBody();
        $queryParams = $this->request->getQueryParams();
        $params = $this->request->getParams();
        if (is_array($parsedBody)) {
            $this->globals = array_merge($this->globals, $parsedBody);
        }
        if (is_array($queryParams)) {
            $this->globals = array_merge($this->globals, $queryParams);
        }
        if (is_array($params)) {
            $this->globals = array_merge($this->globals, $params);
        }
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals() {
        $this->prepareGlobals();
        return $this->globals;
    }
}