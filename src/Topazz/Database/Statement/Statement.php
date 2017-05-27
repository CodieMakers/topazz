<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


use Topazz\Data\Collection;

abstract class Statement {

    protected $values;

    public function __construct() {
        $this->values = new Collection();
    }

    abstract public function getQueryString(): string;

    public function getValues(): array {
        return $this->values->toArray();
    }
}