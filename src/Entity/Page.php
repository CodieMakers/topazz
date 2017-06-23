<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collections\ListInterface;
use Topazz\Database\Statement\Statement;
use Topazz\Theme\Layout;

class Page extends ContentEntity {

    protected static $table = "pages";
    protected static $authorsTable = "page_authors";
    protected static $authorsContentColumnName = "page_id";

    public $name;
    public $title;
    public $uri = "/";
    public $layout_name;
    public $project_id;
    public $content = "";
    public $posts = [];

    public function __construct() {
        parent::__construct();
        if (!is_null($this->id)) {
            $this->refreshPosts();
        }
    }

    public function refreshPosts() {
        $this->posts = Post::find('page_id', $this->id)->toArray();
    }

    public function project(): Project {
        return Project::findById($this->project_id);
    }

    public function setLayout(string $layoutName) {
        // TODO
    }

    protected function create() {
        $this->id = Statement::insert(
            "name", "title", "uri", "layout_name", "project_id", "status"
        )->into("pages")->values(
            $this->name, $this->title, $this->uri, $this->layout_name, $this->project_id, $this->status
        )->prepare()->execute()->lastInsertedId();
    }

    protected function update() {
        Statement::update("pages")
            ->set("name", $this->name)
            ->set("title", $this->title)
            ->set("uri", $this->uri)
            ->set("layout_name", $this->layout_name)
            ->set("project_id", $this->project_id)
            ->set("status", $this->status)
            ->where("id", $this->id)
            ->prepare()->execute();
    }
}