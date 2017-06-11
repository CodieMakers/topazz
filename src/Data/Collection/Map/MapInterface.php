<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Map;


use Topazz\Data\Collection\CollectionInterface;
use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Data\Optional;

interface MapInterface extends CollectionInterface {

    public function keys(): ListInterface;

    public function values(): ListInterface;

    public function hasKey($key): bool;

    public function hasKeys($keys): bool;

    public function hasAnyKey($keys): bool;

    public function set($key, $item): MapInterface;

    public function get($key): Optional;

    public function each(callable $loop): MapInterface;

    public function filter(callable $filter): MapInterface;

    public function map(callable $mapper): MapInterface;
}