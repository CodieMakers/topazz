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
use Topazz\Entity\User;

class UserTest extends TestCase {
    /** @var Generator $faker */
    protected $faker;

    protected function setUp() {
        $this->faker = Factory::create();
    }

    public function testAdmin() {
        /** @var User $admin */
        $admin = User::find("role", User::ROLE_ADMIN)->first()->orNull();

        $this->assertNotNull($admin);
        $this->assertTrue($admin->matchPassword("admin"));
        $this->assertEquals("administrator", $admin->username);
    }

    public function testUserFind() {
        $password = $this->faker->password;
        $user = new User();
        $user->username = $this->faker->userName;
        $user->email = $this->faker->email;
        $user->role = $this->faker->numberBetween(0, 3);
        $user->first_name = $this->faker->firstName;
        $user->last_name = $this->faker->lastName;
        $user->setPassword($password);
        $user->useGravatar();
        $user->save();

        $userOne = User::find("username", $user->username);
        $this->assertEquals(1, $userOne->length());
        $userOne = $userOne->first()->orNull();
        $this->assertObjectHasAttribute("id", $userOne);
        /** @var User $userOne */
        $this->assertEquals($user->id, $userOne->id);
        $this->assertEquals($user->username, $userOne->username);
        $this->assertEquals($user->email, $userOne->email);
        $this->assertEquals($user->first_name, $userOne->first_name);
        $this->assertEquals($user->last_name, $userOne->last_name);
        $this->assertEquals($user->profile_picture, $userOne->profile_picture);
        $this->assertTrue($userOne->matchPassword($password));

        $userTwo = User::find("email", $user->email);
        $this->assertEquals(1, $userTwo->length());
        $userTwo = $userTwo->first()->orNull();
        $this->assertObjectHasAttribute("id", $userTwo);
        /** @var User $userTwo */
        $this->assertEquals($user->id, $userTwo->id);
        $this->assertEquals($user->username, $userTwo->username);
        $this->assertEquals($user->email, $userTwo->email);
        $this->assertEquals($user->first_name, $userTwo->first_name);
        $this->assertEquals($user->last_name, $userTwo->last_name);
        $this->assertEquals($user->profile_picture, $userTwo->profile_picture);
        $this->assertTrue($userTwo->matchPassword($password));
    }

    public function testUserCreate() {
        $user = new User();
        $user->username = $this->faker->userName;
        $user->email = $this->faker->email;
        $user->first_name = $this->faker->firstName;
        $user->last_name = $this->faker->lastName;
        $user->setPassword($this->faker->password);
        $user->profile_picture = "";
        $user->save();

        $this->assertNotNull($user->id);
    }

    /**
     *
     */
    public function testUserUpdate() {
        /** @var User $user */
        $user = User::all()->last()->orNull();
        $newEmail = $this->faker->email;
        $this->assertNotEquals($newEmail, $user->email);

        $user->email = $newEmail;
        $user->save();
        $user = User::find("email", $newEmail)->first()->orNull();

        $this->assertNotNull($user);
        $this->assertEquals($newEmail, $user->email);
    }

    /**
     * @depends testUserUpdate
     */
    public function testUserRemove() {
        /** @var User $user */
        $user = User::all()->last()->orNull();
        $this->assertTrue(User::remove($user->id));
    }
}
