<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collection\Lists\ListInterface;
use Topazz\Database\Proxy\Proxy;

abstract class ContentEntity extends Entity {

    const STATUS_PUBLISHED = 0;
    const STATUS_PRIVATE = 1;
    const STATUS_FOR_REVIEW = 2;

    public $status = self::STATUS_FOR_REVIEW;
    public $create_time;
    public $update_time;

    abstract public function authors(): Proxy;

    public static function published(): ListInterface {
        return static::find("status", self::STATUS_PUBLISHED);
    }
}