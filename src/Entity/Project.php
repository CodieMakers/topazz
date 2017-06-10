<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Slim\Http\Uri;
use Topazz\Data\Optional;
use Topazz\Database\Database;
use Topazz\Database\Proxy\Proxy;
use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;
use Topazz\Theme\Theme;

class Project extends ContentEntity {

    public $id;
    public $name;
    public $host;
    public $theme_id;
    public $theme;

    public function __construct() {
        $this->theme = Theme::find("id", $this->theme_id);
    }

    public function findCurrentPage(Uri $uri): Optional {
        return $this->pages()->fetch()->filter(function (Page $page) use ($uri) {
            return $page->uri === $uri->getPath();
        })->first();
    }

    public function authors(): Proxy {
        return new Proxy(
            Database::select()->from('users')->whereIn('id',
                Database::select('user_id')->distinct()
                    ->from('users_has_projects')
                    ->where('project_id', '=', $this->id)
            ), User::class
        );
    }

    public function pages(): Proxy {
        return new Proxy(
            Database::select()->from('pages')
                ->where('project_id', '=', $this->id),
            Page::class
        );
    }

    public static function getTableDefinition(): Table {
        return Table::create("projects")->columns(
            Column::id(),
            Column::create("name")->varchar()->notNull(),
            Column::create("theme_id")->type("SERIAL")->foreignKey("themes", "id")
        );
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