<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;



use Topazz\Database\Table;

abstract class Query {

    const JOIN_LEFT = 0;
    const JOIN_RIGHT = 1;
    const JOIN_INNER = 2;

    /** @var Table $table */
    protected $table;
    protected $columns;
    /** @var WhereClause $where */
    protected $where;

    /**
     * Query constructor.
     *
     * @param array  $columns
     * @param string $table
     */
    public function __construct(array $columns = [], Table $table) {
        $this->columns = $columns;
        $this->table = $table;
        $this->where = new WhereClause();
    }

    public function where($column, $value, int $comparison = 0, int $glue = 0, bool $not = false) {
        if ($not) {
            $this->where->not();
        }
        $this->where->add($column, $value, $comparison, $glue);
        return $this;
    }

    public function whereNot($column, $value, $comparison, int $glue = 0) {
        return $this->where($column, $value, $comparison, $glue, true);
    }

    /**
     * @return string
     */
    abstract public function getQuery(): string;

    /**
     * @return array
     */
    abstract public function getAttributes(): array;

    public static function select(array $columns, Table $table, $distinct = false) {
        return new SelectQuery($columns, $table, $distinct);
    }

    public static function insert(array $data, Table $table) {
        return new InsertQuery($data, $table);
    }

    public static function update(array $data, Table $table) {
        return new UpdateQuery($data, $table);
    }

    public static function delete(Table $table) {
        return new DeleteQuery($table);
    }
}