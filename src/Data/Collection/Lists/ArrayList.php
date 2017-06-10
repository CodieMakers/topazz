<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Lists;


use Topazz\Data\Optional;
use Topazz\Data\Collection\Stream;
use Topazz\Data\Collection\StreamInterface;

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
        return new Optional($this->items[$index]);
    }

    public function first(): Optional {
        return $this->get(0);
    }

    public function last(): Optional {
        return $this->get($this->length() - 1);
    }

    public function put($item): ListInterface {
        $this->items[] = $item;
        return $this;
    }

    public function putAll(... $items): ListInterface {
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

    public static function fromIterator(\Iterator $iterator) {
        $list = new ArrayList();
        foreach ($iterator as $item) {
            $list->put($item);
        }
        return $list;
    }
}