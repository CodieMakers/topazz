<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


use Topazz\Data\Collection\Lists\ArrayList;
use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Data\Collection\Map\MapInterface;
use Topazz\Data\Collection\StreamInterface;
use Topazz\Data\Optional;

class ColumnCollection implements MapInterface {

    protected $columnNames;
    protected $columns;

    public function __construct(Column... $columns) {
        $this->columns = new ArrayList($columns);
        $this->columnNames = $this->columns->stream()->map(ColumnFilter::class . "::name")->toList();
    }

    public function get($index): Optional {
        return $this->columns->get($this->columnNames->indexOf($index));
    }

    public function stream(): StreamInterface {
        return $this->columns->stream();
    }

    public function collect(callable $collector) {
        return $this->columns->collect($collector);
    }

    public function getIterator() {
        return $this->columns->getIterator();
    }

    public function keys(): ListInterface {
        return $this->columnNames;
    }

    public function values(): ListInterface {
        return $this->columns;
    }

    public function hasKey($key): bool {
        return $this->columnNames->indexOf($key) > -1;
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

    public function set($key, $item): MapInterface {
        if ($this->hasKey($key)) {
            $this->columns->offsetSet($this->columnNames->indexOf($key), $item);
        } else {
            $this->columnNames->put($key);
            $this->columns->put($item);
        }
        return $this;
    }

    function __clone() {
        $clone = clone $this;
        $clone->columnNames = clone $this->columnNames;
        $clone->columns = clone $this->columns;
        return $clone;
    }

    public function offsetExists($offset) {
        return $this->hasKey($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset)->orNull();
    }

    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset) {
        $this->columns->offsetUnset($this->columnNames->indexOf($offset));
        $this->columnNames->offsetUnset($this->columnNames->indexOf($offset));
    }
}