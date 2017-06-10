<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection;


use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Data\Collection\Map\MapInterface;

interface StreamInterface {

    public function filter(callable $filter): StreamInterface;

    public function map(callable $mapper): StreamInterface;

    public function each(callable $loop): StreamInterface;

    public function collect(callable $collector);

    public function toMap(): MapInterface;

    public function toList(): ListInterface;

    public function toArray(): array;

    public function join(string $glue = PHP_EOL): string;
}