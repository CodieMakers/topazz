<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Entity;
use Topazz\Database\Proxy\Proxy;
use Topazz\Database\Query;

abstract class ContentEntity extends Entity {

    const STATUS_PUBLISHED = 0;
    const STATUS_PRIVATE = 1;

    public $status = self::STATUS_PUBLISHED;
    public $authors;

    public function __construct(string $usersHasContentTable) {
        parent::__construct();
        $this->authors = new Proxy(
            Query::create(
                "SELECT " . join(", ", array_merge(
                    array_keys(User::getTable()->getColumns()),
                    [
                        $usersHasContentTable . ".role AS author_role",
                        $usersHasContentTable . ".permission AS author_permission"
                    ]
                )) . " FROM users JOIN " . $usersHasContentTable .
                " ON users.id = " . $usersHasContentTable . ".user_id WHERE " .
                $usersHasContentTable . "." . $this->entityName() . "_id = ?"
            )->addAttribute($this->id),
            User::class
        );
    }

    public static function published() {
        return self::findBy("status", self::STATUS_PUBLISHED);
    }
}