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

    public function type(string $type) {
        $this->type = $type;
        return $this;
    }

    public function notNull() {
        $this->null = false;
        return $this;
    }

    public function unique() {
        $this->unique = true;
        return $this;
    }

    public function unsigned() {
        $this->unsigned = true;
        return $this;
    }

    public function zerofill() {
        $this->zerofill = true;
        return $this;
    }

    public function primary() {
        $this->primary = true;
        return $this;
    }

    public function autoIncrement() {
        $this->autoIncrement = true;
        return $this;
    }

    public function default($value) {
        $this->default = $value;
        return $this;
    }

    public function lock() {
        $this->locked = true;
        return $this;
    }

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