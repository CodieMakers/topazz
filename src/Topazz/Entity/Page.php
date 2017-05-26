<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Table;
use Topazz\Database\TableBuilder;

class Page extends ContentEntity {

    public $name;
    public $title;
    public $uri = "/";
    public $layout;

    public function __construct() {
        parent::__construct("users_has_pages");
    }

    public static function getTable(): Table {
        return (new TableBuilder("pages"))
            ->serial("id")
            ->varchar("name", 50)->notNull()
            ->varchar("title")->null()
            ->integer("status", TableBuilder::TINYINT, 2)->default(self::STATUS_PUBLISHED)
            ->varchar("uri")->notNull()->default("/")
            ->integer("project_id", TableBuilder::BIGINT)->unsigned()->notNull()->foreignKey("projects", "id")
            ->create();
    }

    public function save() {
        // TODO: Implement save() method.
    }
}