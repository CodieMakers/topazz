<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


class Query {

    private $queryString = "";
    private $values = [];

    public function __construct(string $query) {
        $this->queryString = $query;
    }

    public function setAttributes(array $attributes): Query {
        $this->values = $attributes;
        return $this;
    }

    public function addAttribute($key, $value = null): Query {
        if (is_null($value)) {
            $this->values[] = $key;
        } else {
            $this->values[$key] = $value;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->queryString;
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->values;
    }

    public static function create(string $query): Query {
        return new Query($query);
    }
}