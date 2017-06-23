<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */


use Topazz\Application;
use Topazz\Environment;

require_once "vendor/autoload.php";
$app = new Application([
    "configFilename" => "config.test.yml"
]);

echo uniqid();