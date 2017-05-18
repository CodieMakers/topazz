<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Support;


class Collection implements \Iterator {

    private $items = [];
    private $index = 0;

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function get(int $index) {
        return $this->items[$index];
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

    public function forEach(callable $callable) {
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

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() {
        return $this->items[$this->index];
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        ++$this->index;
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return $this->index < $this->length();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        $this->index = 0;
    }

}