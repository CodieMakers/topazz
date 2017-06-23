<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database;


use Topazz\Config\Configuration;
use Topazz\Container;

class Connector {

    protected $container;
    /** @var Configuration $config */
    protected $config;
    protected $connection;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->config = $container->config;
    }

    public function connect(): Database {
        if (is_null($this->connection)) {
            $username = $this->config->get('db.username');
            $password = $this->config->get('db.password');
            $dbName = $this->config->get('db.name');
            $dbHost = $this->config->get('db.host');
            $this->connection = new Database(
                "mysql:host={$dbHost};" . (!is_null($dbName) ? " dbname={$dbName};" : ""),
                $username,
                $password
            );
        }
        return $this->connection;
    }
}