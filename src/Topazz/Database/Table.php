<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use Topazz\Application;
use Topazz\Data\RandomStringGenerator;

class Table {

    protected $columns;
    protected $name;
    /** @var Connector $db */
    private $db;

    public function __construct(string $name, array $columns) {
        $this->name = $name;
        $this->columns = $columns;
        $this->db = Application::getInstance()->getContainer()->get('db');
    }

    /**
     * @return array
     */
    public function getColumns(): array {
        return $this->columns;
    }

    public function getColumnNames(): array {
        return array_keys($this->columns);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    public function getCreateTableStatement() {
        return $this->db->createTable($this->name)->columns($this->columns);
    }
}