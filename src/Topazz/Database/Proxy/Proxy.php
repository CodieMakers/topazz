<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Proxy;


use Topazz\Database\Connector;
use Topazz\Database\Statement\Statement;

class Proxy implements \IteratorAggregate {

    /** @var Statement $statement */
    private $statement;
    private $entityClass;

    public function __construct(Statement $statement, string $entityClass = \stdClass::class) {
        $this->statement = $statement;
        $this->entityClass = $entityClass;
    }

    public function fetch() {
        return Connector::connect()
            ->setEntity($this->entityClass)
            ->setStatement($this->statement)
            ->execute()->all();
    }

    public function getIterator() {
        return $this->fetch()->getIterator();
    }
}