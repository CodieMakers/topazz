<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Config\Config;
use Topazz\Database\Statement\Statement;

class Post extends ContentEntity {

    protected static $table = "posts";
    protected static $authorsTable = "post_authors";
    protected static $authorsContentColumnName = "post_id";

    public $title;
    public $content;
    public $page_id;

    public function __construct() {
        parent::__construct();
        if (is_null($this->content)) {
            $this->content = "";
        }
    }

    public function config(): Config {
        $configs = Statement::select('key', 'value')
            ->from('post_config')
            ->where('post_id', $this->id)
            ->prepare()->execute()->all()->map(function ($configObject) {
                return new Config([
                    $configObject->key => json_decode($configObject->value, true)
                ]);
            })->toArray();
        return new Config($configs);
    }

    public function page(): Page {
        return Page::find('id', $this->page_id)->first()->orNull();
    }

    protected function create() {
        $this->id = Statement::insert(
            "title", "content", "page_id", "status"
        )->into("posts")->values(
            $this->title, $this->content, $this->page_id, $this->status
        )->prepare()->execute()->lastInsertedId();
    }

    protected function update() {
        Statement::update('posts')
            ->set("title", $this->title)
            ->set("content", $this->content)
            ->set("page_id", $this->page_id)
            ->set("status", $this->status)
            ->where("id", $this->id)
            ->prepare()->execute();
    }
}