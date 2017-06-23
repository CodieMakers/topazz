<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections\Stack;


class Node {

    protected $item;
    protected $next;

    public function __construct($item, Node $next = null) {
        $this->item = $item;
        $this->next = $next;
    }

    /**
     * @return Node|null
     */
    public function next() {
        return $this->next;
    }

    /**
     * @return mixed
     */
    public function item() {
        return $this->item;
    }
}