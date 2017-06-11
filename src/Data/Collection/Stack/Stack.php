<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collection\Stack;


class Stack {
    /** @var StackNode|null $topNode */
    protected $topNode;

    public function put($node) {
        $top = new StackNode($node, $this->topNode);
        $this->topNode = $top;
    }

    public function top() {
        $top = $this->topNode;
        if (is_null($top)) {
            return null;
        }
        $this->topNode = $top->getNext();
        return $top->getItem();
    }

    public function hasNext() {
        return !is_null($this->topNode) && !is_null($this->topNode->getNext());
    }
}