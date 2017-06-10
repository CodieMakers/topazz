<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Database\Connector;

abstract class Entity implements EntityInterface {

    public static function all(): ListInterface {
        return Connector::connect()->setEntity(static::class)->setStatement(
            static::getTableDefinition()->getSelectStatement()->all()
        )->execute()->all();
    }

    public static function find(string $key, $value): ListInterface {
        return Connector::connect()->setEntity(static::class)->setStatement(
            static::getTableDefinition()->getSelectStatement()->all()->where($key, $value)
        )->execute()->all();
    }
}