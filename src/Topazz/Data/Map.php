<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Traversable;

class Map extends Collection {

    private $values;
    private $keys;

    public function __construct(array $items = []) {
        parent::__construct($items);
        $this->values = array_values($items);
        $this->keys = array_keys($items);
    }

    public function first() {
        return $this->get($this->keys()->first()->orGet($this->keys[0]));
    }

    public function last() {
        return $this->get($this->keys()->last()->orGet($this->keys[$this->length() - 1]));
    }

    public function put($value) {
        foreach ($value as $k => $v) {
            if (!is_string($k)) {
                throw new \InvalidArgumentException("This must be an associative array");
            }
            $this->keys[] = $k;
            $this->values[] = $v;
            parent::put([$k => $v]);
        }
    }

    public function putAll(Traversable $array) {
        $this->put($array);
    }

    public function keys() {
        return new Collection($this->keys);
    }

    public function toArray() {
        return $this->values;
    }

    public function contains($key) {
        return $this->hasKey($key);
    }

    public function hasKey($key) {
        return in_array($key, $this->keys, true);
    }

    public function hasValue($value) {
        return in_array($value, $this->values, true);
    }
}