<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections;


interface CollectionInterface extends \IteratorAggregate, \ArrayAccess {

    public function get($index);

    public function set($index, $value);

    public function stream(): StreamInterface;
}