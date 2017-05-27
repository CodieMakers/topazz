<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection;
use Topazz\Database\Connector;
use Topazz\Database\Database;

abstract class Entity implements EntityInterface {

    public static function all(): Collection {
        return Connector::connect()->setEntity(static::class)->setStatement(
            Database::select()->from(static::getTable()->getName())
        )->execute()->all();
    }

    public static function find(string $key, $value): Collection {
        $comparison = "=";
        if (is_string($value)) {
            $value = "%" . $value . "%";
            $comparison = "LIKE";
        }
        if (is_bool($value)) {
            $comparison = "IS";
        }
        return Connector::connect()->setEntity(static::class)
            ->setStatement(
                Database::select()->from(static::getTable()->getName())
                    ->where($key, $comparison, $value)
            )->execute()->all();
    }
}