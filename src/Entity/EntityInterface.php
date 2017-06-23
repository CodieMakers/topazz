<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collections\ListInterface;

interface EntityInterface {

    public static function all(): ListInterface;

    public static function find(string $key, $value): ListInterface;

    public static function findById(int $id);

    public function save();

    public static function remove(int $id): bool;
}