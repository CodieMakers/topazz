<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Event;


class Event {

    protected $arguments;
    protected $stopPropagation = false;

    public function __construct(array $arguments = []) {
        $this->arguments = &$arguments;
    }

    public function stopPropagation() {
        $this->stopPropagation = true;
    }

    public function hasPropagationStopped(): bool {
        return $this->stopPropagation;
    }

    public function getArguments(): array {
        return $this->arguments;
    }

    public function withArgumentParam(string $key, $value): Event {
        $clone = clone $this;
        $clone->arguments[$key] = $value;
        return $clone;
    }
}