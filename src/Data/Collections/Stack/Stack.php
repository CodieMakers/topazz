<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data\Collections\Stack;


class Stack {
    /** @var Node $top */
    protected $top;

    public function add($item) {
        $this->top = new Node($item, $this->top);
    }

    public function hasNext(): bool {
        return !is_null($this->top);
    }

    public function top() {
        if ($this->hasNext()) {
            $topNode = $this->top;
            $this->top = $topNode->next();
            return $topNode->item();
        }
        return null;
    }

    public static function fromArray(array $items): Stack {
        $stack = new Stack();
        foreach ($items as $item) {
            $stack->add($item);
        }
        return $stack;
    }
}