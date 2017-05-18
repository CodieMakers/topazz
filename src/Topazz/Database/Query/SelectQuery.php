<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Query;


use Topazz\Database\Table;

class SelectQuery extends Query {

    const ORDER_NONE = 0;
    const ORDER_ASC = 1;
    const ORDER_DESC = 2;

    private $distinct;
    private $limit;
    private $order = [];
    private $group = [];
    private $join = [];

    public function __construct(array $columns = ["*"], Table $table, bool $distinct = false) {
        parent::__construct($columns, $table);
        $this->distinct = $distinct;
    }

    public function limit(int $limit) {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy($column, int $order = 0) {
        $this->order[] = $column . (
            $order > self::ORDER_NONE ?
                " " . ($order == self::ORDER_ASC ? "ASC" : "DESC") :
                ""
            );
        return $this;
    }

    public function groupBy(array $columns) {
        $this->group = $columns;
        return $this;
    }

    public function join($leftTable, $rightTable, $leftColumn, $rightColumn, int $joinType = 0) {
        $join = "";
        switch ($joinType) {
            case self::JOIN_LEFT:
                $join .= "LEFT JOIN ";
                break;
            case self::JOIN_RIGHT:
                $join .= "RIGHT JOIN ";
                break;
            case self::JOIN_INNER:
                $join .= "INNER JOIN ";
                break;
            default:
                $join .= "FULL OUTER JOIN ";
                break;
        }
        $join .= $leftTable . " ON " . $leftTable . "." . $leftColumn . " = " . $rightTable . "." . $rightColumn;
        $this->join[] = $join;
        return $this;
    }

    public function getQuery(): string {
        $query = "SELECT " . ($this->distinct ? "DISTINCT " : "") . implode(", ", $this->columns) . " FROM " . $this->table;
        if (!empty($this->join)) {

        }
        if (!empty($this->group)) {
            $query .= " GROUP BY " . implode(", ", $this->group) . $this->where->getWhereClause(true);
        } else {
            $query .= $this->where->getWhereClause();
        }
        if (!empty($this->order)) {
            $query .= " ORDER BY " . implode(", ", $this->order);
        }
        if (is_int($this->limit)) {
            $query .= " LIMIT " . $this->limit;
        }
        return $query;
    }


    public function getAttributes(): array {
        return $this->where->getAttributes();
    }
}