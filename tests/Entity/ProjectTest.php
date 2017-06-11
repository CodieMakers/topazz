<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Tests\Entity;

use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Topazz\Entity\Page;
use Topazz\Entity\Project;

class ProjectTest extends TestCase {

    private $project;
    private $faker;

    protected function setUp() {
        $this->faker = new Generator();
        $this->project = new Project();
        $this->project->name = $this->faker->name;
        $this->project->host = "app.topazz.dev";
        $page = new Page();
        $page->uri = "/";
        $this->project->addPage($page);
    }
}
