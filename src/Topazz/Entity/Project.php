<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Slim\Http\Uri;
use Topazz\Database\Proxy\SingleToManyProxy;
use Topazz\Database\Table;
use Topazz\Database\TableBuilder;
use Topazz\Theme\Theme;

class Project extends ContentEntity {

    public $name;
    public $uri;
    protected $theme_id;
    public $theme;
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
        $this->theme = Theme::findById($this->theme_id)->orNull();
    }

    public function getCurrentPage(Uri $uri) {
        return $this->pages->all()->filter(function (Page $page) use ($uri) {
            return $page->uri === $uri->getPath();
        });
    }

    public static function getTable(): Table {
        return (new TableBuilder("projects"))
            ->serial("id")
            ->varchar("name", 45)->notNull()
            ->varchar("uri")->notNull()->default($_SERVER["HTTP_HOST"])
            ->integer("theme_id", TableBuilder::BIGINT)->foreignKey("themes", "id")
            ->create();
    }

    public static function findByUri(Uri $uri) {
        return self::findBy("uri", $uri->getHost());
    }

    public function save() {
        // TODO: Implement save() method.
    }
}