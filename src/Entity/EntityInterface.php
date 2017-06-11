<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Database\Table\Table;

interface EntityInterface {

    public static function all(): ListInterface;

    public static function find(string $key, $value): ListInterface;

    public static function getTableDefinition(): Table;

    public function create();

    public function update();

    public function remove();
}