<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


class ColumnFilter {

    public static function primary(Column $column) {
        return $column->isPrimary();
    }

    public static function foreign(Column $column) {
        return $column->isForeign();
    }

    public static function name(Column $column) {
        return $column->getName();
    }
}