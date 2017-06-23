<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections\Streams;


use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Data\Collections\ListInterface;
use Topazz\Data\Collections\Maps\HashMap;
use Topazz\Data\Collections\MapInterface;
use Topazz\Data\Collections\StreamInterface;

class Stream implements StreamInterface {

    protected $items = [];

    public function __construct(array $items) {
        $this->items = $items;
    }

    public function filter(callable $filter): StreamInterface {
        $clone = clone $this;
        $clone->items = [];
        foreach ($this->items as $index => $item) {
            if (call_user_func($filter, $item, $index)) {
                $clone->items[] = $item;
            }
        }
        return $clone;
    }

    public function map(callable $mapper): StreamInterface {
        $clone = clone $this;
        foreach ($this->items as $index => $item) {
            $clone->items[$index] = call_user_func($mapper, $item, $index);
        }
        return $clone;
    }

    public function each(callable $loop): StreamInterface {
        foreach ($this->items as $index => $item) {
            call_user_func($loop, $item, $index);
        }
        return $this;
    }

    public function collect(callable $collector) {
        return call_user_func($collector, $this->items);
    }

    public function toMap(): MapInterface {
        return new HashMap($this->items);
    }

    public function toList(): ListInterface {
        return new ArrayList($this->items);
    }

    public function toArray(): array {
        return $this->items;
    }

    public function toString(string $glue = PHP_EOL): string {
        return join($glue, $this->items);
    }
}