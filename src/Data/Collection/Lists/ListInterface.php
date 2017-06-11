<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Lists;


use Topazz\Data\Collection\CollectionInterface;
use Topazz\Data\Optional;

interface ListInterface extends CollectionInterface {

    public function get($index): Optional;

    public function first(): Optional;

    public function last(): Optional;

    public function length(): int;

    public function toArray(): array;

    public function indexOf($item): int;

    public function put($item): ListInterface;

    public function putAll(... $items): ListInterface;

    public function each(callable $loop): ListInterface;

    public function filter(callable $filter): ListInterface;

    public function map(callable $mapper): ListInterface;
}