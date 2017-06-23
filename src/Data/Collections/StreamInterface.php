<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections;



interface StreamInterface {

    public function filter(callable $filter): StreamInterface;

    public function map(callable $mapper): StreamInterface;

    public function each(callable $loop): StreamInterface;

    public function collect(callable $collector);

    public function toMap(): MapInterface;

    public function toList(): ListInterface;

    public function toString(string $glue = PHP_EOL): string;

    public function toArray(): array;
}