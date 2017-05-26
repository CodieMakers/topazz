<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Table;
use Topazz\Database\TableBuilder;

class Post extends ContentEntity {

    public static function getTable(): Table {
        return (new TableBuilder("posts"))
            ->serial('id')
            ->varchar('title')->notNull()
            ->text('body')
            ->integer('status', TableBuilder::TINYINT, 2)->default(self::STATUS_PUBLISHED)
            ->timestamp('create_time')->default('CURRENT_TIMESTAMP')
            ->timestamp('update_time')->default('CURRENT_TIMESTAMP')->check('create_time <= update_time')
            ->create();
    }

    public function save() {
        // TODO: Implement save() method.
    }
}