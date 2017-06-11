<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */


use Topazz\Application;

$classLoader = require __DIR__ . "/vendor/autoload.php";
$app = new Application($classLoader);