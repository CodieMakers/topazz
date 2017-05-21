<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Traversable;

class Collection implements \IteratorAggregate {

    private $items = [];
    private $index = 0;

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function get(int $index) {
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

    public function forEach (callable $callable) {
        foreach ($this->items as $index => $item) {
            call_user_func($callable, $item, $index);
        }
        return $this;
    }

    public function map(callable $mapper) {
        foreach ($this->items as $index => $item) {
            $this->items[$index] = call_user_func($mapper, $item, $index);
        }
        return $this;
    }

    public function filter(callable $filter) {
        $this->items = array_filter($this->items, $filter, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    public function put($value) {
        $this->items[] = $value;
    }

    public function putAll(Traversable $array) {
        foreach ($array as $value) {
            $this->put($value);
        }
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