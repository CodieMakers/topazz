<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Map;

use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Data\Collection\Stream;
use Topazz\Data\Collection\StreamInterface;
use Topazz\Data\Optional;

class Map implements MapInterface {

    protected $keys;
    protected $values;

    public function __construct(array $items = []) {
        $this->keys = new ArrayList(array_keys($items));
        $this->values = new ArrayList(array_values($items));
    }

    public function set($key, $item): MapInterface {
        if ($this->hasKey($key)) {
            $this->values[$this->keys->indexOf($key)] = $item;
        } else {
            $this->keys->put($key);
            $this->values->put($item);
        }
        return $this;
    }

    public function get($key): Optional {
        if ($this->hasKey($key)) {
            return $this->values->get($this->keys->indexOf($key));
        }
        return new Optional();
    }

    public function keys(): ListInterface {
        return $this->keys;
    }

    public function values(): ListInterface {
        return $this->values;
    }

    public function hasKey($key): bool {
        return $this->keys->indexOf($key) > -1;
    }

    public function hasKeys($keys): bool {
        foreach ($keys as $key) {
            if (!$this->hasKey($key)) {
                return false;
            }
        }
        return true;
    }

    public function hasAnyKey($keys): bool {
        foreach ($keys as $key) {
            if ($this->hasKey($key)) {
                return true;
            }
        }
        return false;
    }

    public function stream(): StreamInterface {
        return new Stream($this->values->toArray());
    }

    public function collect(callable $collector) {
        return call_user_func($collector, $this->values->toArray());
    }

    public function each(callable $loop): MapInterface {
        foreach ($this->keys as $key) {
            call_user_func($loop, $key, $this->values->offsetGet($this->keys->indexOf($key)));
        }
        return $this;
    }

    public function filter(callable $filter): MapInterface {
        $clone = new Map();
        foreach ($this->keys as $key) {
            $index = $this->keys->indexOf($key);
            if (call_user_func($filter, $key, $this->values->offsetGet($index))) {
                $clone->set($key, $this->values->offsetGet($index));
            }
        }
        return $clone;
    }

    public function map(callable $mapper): MapInterface {
        $clone = clone $this;
        foreach ($this->keys as $key) {
            $clone->set($key, call_user_func(
                $mapper,
                $key,
                $this->values->offsetGet($this->keys->indexOf($key))
            ));
        }
        return $clone;
    }

    public function __clone() {
        $clone = clone $this;
        $clone->keys = clone $this->keys;
        $clone->values = clone $this->values;
        return $clone;
    }

    public function getIterator() {
        return $this->values->getIterator();
    }

    public function offsetExists($offset) {
        return $this->values->offsetExists($this->keys->indexOf($offset));
    }

    public function offsetGet($offset) {
        return $this->get($offset)->orNull();
    }

    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset) {
        $this->values->offsetUnset($this->keys->indexOf($offset));
    }
}