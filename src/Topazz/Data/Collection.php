<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Traversable;

class Collection implements \IteratorAggregate {

    protected $items = [];

    public function __construct(array $items = []) {
        $this->items = $items;
    }

    public function get($index) {
        return new Optional($this->items[$index]);
    }

    public function first() {
        return $this->get(0);
    }

    public function last() {
        return $this->get($this->length() - 1);
    }

    public function length() {
        return count($this->items);
    }

    public function toArray() {
        return $this->items;
    }

    public function join($glue) {
        return join($glue, $this->items);
    }

    public function forEach (callable $callable) {
        foreach ($this->items as $index => $item) {
            call_user_func($callable, $item, $index);
        }
        return $this;
    }

    public function map(callable $mapper) {
        $clone = clone $this;
        foreach ($clone->items as $index => $item) {
            $clone->items[$index] = call_user_func($mapper, $item, $index);
        }
        return $clone;
    }

    public function filter(callable $filter) {
        $clone = clone $this;
        $clone->items = array_filter($clone->items, $filter, ARRAY_FILTER_USE_BOTH);
        return $clone;
    }

    public function filterEmpty() {
        return $this->filter(function ($item) {
            return !is_null($item) && ((!is_array($item) && !empty($item)) || is_array($item));
        });
    }

    public function put($value) {
        $this->items[] = $value;
    }

    public function putAll(Traversable $array) {
        foreach ($array as $value) {
            $this->put($value);
        }
    }

    public function contains($key) {
        return !$this->isEmpty() && in_array($key, $this->items, true);
    }

    /**
     * @param \Iterator|array $keys
     * @param bool            $strict
     *
     * @return bool
     */
    public function containsAll($keys) {
        foreach ($keys as $key) {
            if (!$this->contains($key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param \Iterator|array $keys
     * @param bool            $strict
     *
     * @return bool
     */
    public function containsAny($keys) {
        foreach ($keys as $key) {
            if ($this->contains($key)) {
                return true;
            }
        }
        return false;
    }

    public function hasKey($key) {
        return !$this->isEmpty() && array_key_exists($key, $this->items);
    }

    public function hasAllKeys($keys) {
        foreach ($keys as $key) {
            if (!$this->hasKey($key)) {
                return false;
            }
        }
        return true;
    }

    public function hasAnyKey($keys) {
        foreach ($keys as $key) {
            if ($this->hasKey($key)) {
                return true;
            }
        }
        return false;
    }

    public function isEmpty() {
        return $this->length() == 0;
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator() {
        return new \ArrayIterator($this->items);
    }
}