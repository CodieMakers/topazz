<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Config;


interface ConfigInterface extends \IteratorAggregate {

    public function get(string $key);

    public function exists(string $key): bool;

    public function set(string $key, $value);

    public function remove(string $key);

    public function toArray(): array;
}