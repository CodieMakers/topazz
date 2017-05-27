<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Database\Table\Column;
use Topazz\Entity\Entity;
use Topazz\Database\Table\Table;
use Topazz\Entity\Page;
use Topazz\View\Renderer;

abstract class Theme extends Entity {

    public $id;
    public $name;
    public $activated = true;
    protected $renderer;

    public function __construct() {
        $this->renderer = Renderer::getInstance();
    }

    abstract public function render(Page $page): string;

    abstract public static function getLayouts(): array;

    public static function getTable(): Table {
        return Table::create("themes")->addColumns(
            Column::id(),
            Column::create("name")->type("VARCHAR(50)")->notNull(),
            Column::create("activated")->type("BOOLEAN")->default(true)
        );
    }


}