<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Database\Table;


class Column {

    protected $name;
    protected $type;
    protected $locked = false;

    protected $null = true;
    protected $unique = false;
    protected $unsigned = false;
    protected $zerofill = false;
    protected $primary = false;
    protected $autoIncrement = false;
    protected $foreign = false;

    protected $reference;
    protected $default;
    protected $onUpdate;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function type(string $type): Column {
        $this->type = $type;
        return $this;
    }

    public function notNull(): Column {
        $this->null = false;
        return $this;
    }

    public function unique(): Column {
        $this->unique = true;
        return $this;
    }

    public function unsigned(): Column {
        $this->unsigned = true;
        return $this;
    }

    public function zerofill(): Column {
        $this->zerofill = true;
        return $this;
    }

    public function primary(): Column {
        $this->primary = true;
        return $this;
    }

    public function autoIncrement(): Column {
        $this->autoIncrement = true;
        return $this;
    }

    public function default($value): Column {
        $this->default = $value;
        return $this;
    }

    public function onUpdate(string $function): Column {
        if (isset($this->default) && preg_match('/(TIMESTAMP|DATETIME)/', $this->type)) {
            $this->onUpdate = $function;
        }
        return $this;
    }

    public function lock(): Column {
        $this->locked = true;
        return $this;
    }

    public function references(string $table, string $column): Column {
        $this->foreign = true;
        $this->reference = "`{$table}` (`{$column}`)";
        return $this;
    }

    //---------- GETTERS

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function isNull(): bool {
        return $this->null;
    }

    public function isUnique(): bool {
        return $this->unique;
    }

    public function isUnsigned(): bool {
        return $this->unsigned;
    }

    public function isZerofill(): bool {
        return $this->zerofill;
    }

    public function isPrimary(): bool {
        return $this->primary;
    }

    public function isForeign(): bool {
        return $this->foreign;
    }

    public function hasDefault() {
        return !is_null($this->default);
    }

    public function isLocked(): bool {
        return $this->locked;
    }

    public function getDefinition(): string {
        // TODO: implement Columns' getDefinition() method
        return "`{$this->name}` {$this->type}";
    }

    public static function id() {
        $id = new Column("id");
        return $id->type("BIGINT")->primary()->autoIncrement()->unique()->unsigned();
    }

    public static function create(string $name) {
        return new Column($name);
    }
}