<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Tests\Entity;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Topazz\Entity\Project;

class ProjectTest extends TestCase {

    /** @var Generator $faker */
    private $faker;

    protected function setUp() {
        $this->faker = Factory::create();
    }

    public function testCreateProject() {
        $project = new Project();
        $project->name = $this->faker->name;
        $project->host = "app.topazz.dev";
        $project->setTheme("simple-theme");
    }
}
