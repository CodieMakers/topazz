<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Theme;


use Topazz\Database\Entity;
use Topazz\Database\Table;
use Topazz\Database\TableBuilder;
use Topazz\Entity\Page;

abstract class Theme extends Entity {

    public $name;
    public $activated = true;

    abstract public function render(Page $page): string;

    abstract public static function getAvailableLayouts(): array;

    public static function getTable(): Table {
        return (new TableBuilder("themes"))
            ->serial('id')
            ->boolen('activated')->default(true)
            ->create();
    }


}