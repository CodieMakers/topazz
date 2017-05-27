<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Database;
use Topazz\Database\Proxy\Proxy;
use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;

class Post extends ContentEntity {

    public $id;
    public $title;
    public $body;
    public $uri;
    protected $project_id;

    public static function getTable(): Table {
        return Table::create("posts")->addColumns(
            Column::id()
        );
    }

    public function authors(): Proxy {
        return new Proxy(
            Database::select()->from('users')->whereIn('id',
                Database::select('user_id')->distinct()
                    ->from('users_has_posts')
                    ->where('post_id', '=', $this->id)
            ), User::class
        );
    }

    public function project(): Project {
        return Project::find('id', $this->project_id)->first()->orNull();
    }

    public function content(): string {
        return "";
    }

    public function create() {
        // TODO: Implement create() method.
    }

    public function update() {
        // TODO: Implement update() method.
    }

    public function remove() {
        // TODO: Implement remove() method.
    }
}