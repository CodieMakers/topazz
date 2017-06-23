<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Data\Collections\Lists\ArrayList;
use Topazz\Data\Optional;
use Topazz\Database\Statement\Statement;
use Topazz\TopazzApplicationException;

class Project extends ContentEntity {

    protected static $table = "projects";
    protected static $authorsTable = "project_authors";
    protected static $authorsContentColumnName = "project_id";
    protected $config;

    public $name;
    public $host;
    public $theme_name;
    public $pages = [];

    public function __construct() {
        parent::__construct();
        $this->config = $this->container->config;
        if (!isset($this->host)) {
            $this->host = $this->config->get('system.default_host');
        }
        if (!is_null($this->id)) {
            $this->refreshPages();
        }
    }

    public function refreshPages() {
        $this->pages = Page::find('project_id', $this->id)->toArray();
    }

    public function page(string $path): Optional {
        return ArrayList::fromArray($this->pages)->filter(function (Page $page) use ($path) {
            return $page->uri === $path;
        })->first();
    }

    public function hasPage(string $path) {
        return !$this->page($path)->isNull();
    }

    public function addPage(Page $page) {
        $page->project_id = $this->id;
        $page->update();
        $this->refreshPages();
    }

    public function removePage(Page $page) {
        if ($page->project_id !== $this->id) {
            throw new TopazzApplicationException("This page does not belong to this project");
        }
        $this->pages_changed = true;
        Page::remove($page->id);
    }

    public function setTheme(string $themeName) {
        if (in_array($themeName, $this->config->get('themes.active'))) {
            $this->theme_name = $themeName;
        } else {
            throw new TopazzApplicationException(sprintf("There is not a theme '%s' installed or active", $themeName));
        }
    }

    public function render(Request $request, Response $response): Response {
        $renderer = $this->container->renderer;
        /** @var Page $page */
        $page = $this->page($request->getUri()->getPath())->orNull();
        $this->container->themes->loadTheme($this->theme_name);
        $templateName = "@{$this->theme_name}/{$page->layout_name}.layout.twig";
        return $renderer->display($request, $response, $templateName, [
            "page_title" => $page->name,
            "project" => $this,
            "page" => $page,
            "posts" => Statement::select()->from('posts')
                ->where('page_id', $page->id)
                ->where('status', ContentEntity::STATUS_PUBLISHED)
                ->prepare(Post::class)->execute()->all()->toArray()
        ]);
    }

    protected function create() {
        $this->id = Statement::insert(
            "name",
            "host",
            "theme_name",
            "status"
        )->into("projects")->values(
            $this->name,
            $this->host,
            $this->theme_name,
            $this->status
        )->prepare()->execute()->lastInsertedId();
    }

    protected function update() {
        Statement::update("projects")
            ->set("name", $this->name)
            ->set("host", $this->host)
            ->set("theme_name", $this->theme_name)
            ->set("status", $this->status)
            ->where('id', $this->id)
            ->prepare()->execute();
    }

    public static function findByHost(string $host): Optional {
        return self::find("host", $host)->first();
    }
}