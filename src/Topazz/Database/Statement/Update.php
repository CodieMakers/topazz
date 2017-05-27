<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


class Update extends BasicStatement {

    protected $what;

    public function __construct($what) {
        parent::__construct();
        $this->what = $what;
    }

    public function getQueryString(): string {
        // TODO: Implement getQueryString() method.
    }
}