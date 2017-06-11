<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection;


interface CollectionInterface extends \IteratorAggregate, \ArrayAccess {

    public function stream(): StreamInterface;

    public function collect(callable $collector);
}