<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Tests\Entity;


use PHPUnit\Framework\TestCase;
use Topazz\Entity\User;

class UserTest extends TestCase {

    /** @var User $admin */
    private $admin;

    protected function setUp() {
        $this->admin = User::find("role", User::ROLE_ADMIN)->first()->orCall(function () {
            $admin = new User();
            $admin->role = User::ROLE_ADMIN;
            $admin->username = "admin";
            $admin->email = "admin@example.com";
            $admin->setPassword("admin");
            $admin->create();
            return $admin;
        });
    }

    public function testAdminPassword() {
        self::assertNotNull($this->admin);
        $this->assertTrue($this->admin->matchPassword("admin"));
    }
}
