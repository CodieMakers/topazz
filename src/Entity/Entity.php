<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection\Lists\ListInterface;

abstract class Entity implements EntityInterface {

    public static function all(): ListInterface {
        return static::getTableDefinition()->getSelect()->all()
            ->setEntity(static::class)->execute()->all();
    }

    public static function find(string $key, $value): ListInterface {
        return static::getTableDefinition()->getSelect()->all()->where($key, $value)
            ->setEntity(static::class)->execute()->all();
    }
}