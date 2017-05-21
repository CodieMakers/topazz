<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Proxy\SingleToManyProxy;
use Topazz\Database\Table;
use Topazz\Database\TableBuilder;

class Project extends ContentEntity {

    public $name;
    public $uri;
    public $pages;
    public $authors;

    public function __construct() {
        parent::__construct("users_has_projects");
        $this->pages = new SingleToManyProxy(
            "pages",
            "project_id",
            $this->id,
            Page::class
        );
    }

    public static function getTable(): Table {
        return (new TableBuilder("projects"))
            ->serial("id")
            ->varchar("name", 45)->notNull()
            ->varchar("uri")->notNull()->default($_SERVER["HTTP_HOST"])
            ->create();
    }

    public function save() {
        // TODO: Implement save() method.
    }
}