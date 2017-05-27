<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection;
use Topazz\Database\Table\Table;

interface EntityInterface {

    public static function all(): Collection;
    public static function find(string $key, $value): Collection;
    public static function getTable(): Table;

    public function create();
    public function update();
    public function remove();
}