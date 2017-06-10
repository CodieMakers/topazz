<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Topazz\Application;
use Topazz\Module\Module;

class ResourceFinder {

    private static $defaultResourceMapping = [
        "css://" => ["public/css"],
        "js://" => ["public/js"],
        "project://" => []
    ];

    protected $mapping = [];

    public function __construct() {
        $this->mapping = self::$defaultResourceMapping;
        // TODO: map current Project
    }

    public function getRealPath(string $url): string {
        $scheme = preg_split('/:\/\//',$url)[0];
    }
}