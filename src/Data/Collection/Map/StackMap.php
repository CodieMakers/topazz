<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Map;


use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Data\Collection\StreamInterface;
use Topazz\Data\Optional;
use Traversable;

class StackMap implements MapInterface {

    protected $keys;
    protected $stacks;

    public function __construct(array $items = []) {

    }

    public function stream(): StreamInterface {
        // TODO: Implement stream() method.
    }

    public function collect(callable $collector) {
        // TODO: Implement collect() method.
    }

    public function getIterator() {
        // TODO: Implement getIterator() method.
    }

    public function offsetExists($offset) {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset) {
        // TODO: Implement offsetGet() method.
    }

    public function offsetSet($offset, $value) {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset) {
        // TODO: Implement offsetUnset() method.
    }

    public function keys(): ListInterface {
        // TODO: Implement keys() method.
    }

    public function values(): ListInterface {
        // TODO: Implement values() method.
    }

    public function hasKey($key): bool {
        // TODO: Implement hasKey() method.
    }

    public function hasKeys($keys): bool {
        // TODO: Implement hasKeys() method.
    }

    public function hasAnyKey($keys): bool {
        // TODO: Implement hasAnyKey() method.
    }

    public function set($key, $item): MapInterface {
        // TODO: Implement set() method.
    }
}