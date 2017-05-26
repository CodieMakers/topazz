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

    /** @var User $user */
    private $user;

    /**
     * @inheritDoc
     */
    protected function setUp() {
        parent::setUp();
        $this->user = User::findById(1)->orNull();
    }

    public function testUserPassword() {
        self::assertNotNull($this->user);
        $this->assertTrue($this->user->matchPassword("admin"));
    }


}
