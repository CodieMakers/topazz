<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Slim\Http\Uri;
use Topazz\Data\Optional;
use Topazz\Database\Proxy\Proxy;
use Topazz\Database\Statement\Statement;
use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;

class Project extends ContentEntity {

    public $id;
    public $name;
    public $host;
    public $theme_name;

    public function findCurrentPage(Uri $uri): Optional {
        return $this->pages()->fetch()->stream()->filter(function (Page $page) use ($uri) {
            return $page->uri === $uri->getPath();
        })->toList()->first();
    }

    public function addPage(Page $page) {
        $page->project_id = $this->id;
        $page->update();
    }

    public function addAuthor(User $user) {
        Statement::insert("user_id", "project_id")
            ->into("project_authors")
            ->values($user->id, $this->id)
            ->execute();
        // TODO: check affectedRows
    }

    public function authors(): Proxy {
        return new Proxy(
            User::getTableDefinition()->getSelect()
                ->where('id',
                    Statement::select('user_id')
                        ->from('project_authors')
                        ->where('project_id', $this->id)
                ), User::class
        );
    }

    public function pages(): Proxy {
        return new Proxy(
            Page::getTableDefinition()->getSelect()
                ->where('project_id', $this->id),
            Page::class
        );
    }

    public static function getTableDefinition(): Table {
        return Table::create("projects")->columns(
            Column::id(),
            Column::create("name")->type("VARCHAR(255)")->notNull(),
            Column::create("host")->type("VARCHAR(255)")->default($_SERVER["HTTP_HOST"]),
            Column::create("theme_name")->type("VARCHAR(255)"),
            Column::create("create_time")->type("TIMESTAMP")->notNull()->default("CURRENT_TIMESTAMP"),
            Column::create("update_time")->type("TIMESTAMP")->notNull()->default("CURRENT_TIMESTAMP")->onUpdate("CURRENT_TIMESTAMP")
        );
    }

    public function create() {
        self::getTableDefinition()
            ->getInsert("name", "host", "theme_name")
            ->values($this->name, $this->host, $this->theme_name)
            ->execute();
    }

    public function update() {
        self::getTableDefinition()
            ->getUpdate()
            ->set("name", $this->name)
            ->set("host", $this->host)
            ->set("theme_name", $this->theme_name)
            ->execute();
    }

    public function remove() {
        self::getTableDefinition()
            ->getDelete()
            ->where("id", $this->id)
            ->execute();
    }
}