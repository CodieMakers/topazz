<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Stack;


class StackNode {

    protected $item;
    protected $next;

    public function __construct($item, StackNode $next = null) {
        $this->item = $item;
        $this->next = $next;
    }

    public function getItem() {
        return $this->item;
    }

    public function getNext() {
        return $this->next;
    }

    public function setNext(StackNode $next) {
        $this->next = $next;
    }
}