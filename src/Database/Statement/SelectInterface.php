<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Statement;



interface SelectInterface extends StatementInterface {

    public function all(): SelectInterface;

    public function columns(string... $columnNames): SelectInterface;

    public function distinct(): SelectInterface;

    public function where(string $columnName, $value, $chain = "AND"): SelectInterface;

    public function groupBy(string... $columnNames): SelectInterface;

    public function limit(int $limit, int $offset = 0): SelectInterface;
}