<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Proxy;


use Topazz\Application;
use Topazz\Database\Connector;
use Topazz\Database\Optional;
use Topazz\Database\Query;

class Proxy {

    /** @var Connector $db */
    private $db;
    /** @var Query $query */
    private $query;
    private $entityClass;

    public function __construct(Query $query, string $entityClass) {
        $this->db = Application::getInstance()->getContainer()->get('db');
        $this->query = $query;
        $this->entityClass = $entityClass;
    }

    public function all() {
        return $this->db->query($this->query)->run($this->entityClass)->all();
    }
}