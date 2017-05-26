<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

require_once dirname(__DIR__) . "/vendor/autoload.php";

(new \Symfony\Component\Dotenv\Dotenv())->load(".env.sample");

$app = new \Topazz\Application();