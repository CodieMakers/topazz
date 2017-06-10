<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;


interface StatementInterface {

    public function getValues(): array;

    public function getQueryString(): string;
}