<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections\Lists;


use Topazz\Data\Collections\ListInterface;
use Topazz\Data\Collections\StreamInterface;
use Topazz\Data\Collections\Streams\Stream;
use Topazz\Data\Optional;

class ArrayList implements ListInterface {

    protected $items = [];

    public function __construct($length = null) {
        if (is_int($length)) {
            for ($i = 0; $i < $length; $i++) {
                $this->items[] = new \stdClass();
            }
        } elseif ($length instanceof \ArrayAccess || is_array($length)) {
            $this->items = $length;
        }
    }

    public function get($index): Optional {
        $item = null;
        if (isset($this->items[$index])) {
            $item = $this->items[$index];
        }
        return new Optional($item);
    }

    public function first(): Optional {
        return $this->get(0);
    }

    public function last(): Optional {
        return $this->get($this->length() - 1);
    }

    public function set($index, $value): ListInterface {
        $this->items[$index] = $value;
        return $this;
    }

    public function put($item): ListInterface {
        $this->items[] = $item;
        return $this;
    }

    public function putAll(... $items): ListInterface {
        if (count($items) === 1 && is_array($items[0])) {
            $items = $items[0];
        }
        foreach ($items as $item) {
            $this->put($item);
        }
        return $this;
    }

    public function indexOf($item): int {
        $index = array_search($item, $this->items, true);
        return $index === false ? -1 : $index;
    }

    public function length(): int {
        return count($this->items);
    }

    public function stream(): StreamInterface {
        return new Stream($this->items);
    }

    public function collect(callable $collector) {
        return call_user_func($collector, $this->items);
    }

    public function map(callable $mapper): ListInterface {
        $clone = clone $this;
        foreach ($this->items as $index => $item) {
            $clone[$index] = call_user_func($mapper, $item, $index);
        }
        return $clone;
    }

    public function filter(callable $filter): ListInterface {
        $clone = new ArrayList();
        foreach ($this->items as $index => $item) {
            if (call_user_func($filter, $item, $index)) {
                $clone->put($item);
            }
        }
        return $clone;
    }

    public function each(callable $loop): ListInterface {
        foreach ($this->items as $index => $item) {
            call_user_func($loop, $item, $index);
        }
        return $this;
    }

    public function toArray(): array {
        return $this->items;
    }

    public function getIterator() {
        return new \ArrayIterator($this->items);
    }

    public function offsetExists($offset) {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset) {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value) {
        return $this->items[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->items[$offset]);
    }

    public static function fromArray(array $items) {
        return new ArrayList($items);
    }

    public static function fromIterator(\Iterator $iterator) {
        $list = new ArrayList();
        foreach ($iterator as $item) {
            $list->put($item);
        }
        return $list;
    }
}