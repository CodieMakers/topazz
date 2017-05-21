<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Topazz\Entity\Page;
use Topazz\Entity\Project;

class ProjectTest extends TestCase {

    private $project;

    protected function setUp() {
        $this->project = new Project();
        $this->project->name = "Dummy Project";
        $page = new Page();
        $page->uri = "/";
    }

    public function testPages() {

    }
}
