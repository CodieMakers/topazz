<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Table\Column;
use Topazz\Database\Table\Table;

class User extends Entity {

    const ROLE_ADMIN = 0;
    const ROLE_MODERATOR = 1;
    const ROLE_EDITOR = 2;
    const ROLE_BLOGGER = 3;

    protected static $permissions = [
        self::ROLE_MODERATOR => [],
        self::ROLE_EDITOR => [],
        self::ROLE_BLOGGER => []
    ];

    public $id;
    public $username;
    public $email;
    public $role = self::ROLE_BLOGGER;
    public $first_name;
    public $last_name;
    public $profile_picture = "/public/img/profile.png";
    private $password;

    public function __construct() {
        self::fetchPermissions();
    }

    public function setPassword(string $password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function matchPassword(string $password) {
        return password_verify($password, $this->password);
    }

    public function hasPermission(string $permission): bool {
        if ($this->role == self::ROLE_ADMIN) {
            return true;
        }
        return in_array($permission, self::$permissions[$this->role]);
    }

    public static function getTable(): Table {
        return Table::create("users")->addColumns(
            Column::id(),
            Column::create("username")->type("VARCHAR(50)")->unique()->notNull(),
            Column::create("password")->type("VARCHAR(255)")->notNull(),
            Column::create("email")->type("VARCHAR(50)")->unique()->notNull(),
            Column::create("role")->type("INTEGER(3)")->unsigned()->notNull()->default(self::ROLE_BLOGGER),
            Column::create("first_name")->type("VARCHAR(50)"),
            Column::create("last_name")->type("VARCHAR(50)"),
            Column::create("profile_picture")->type("VARCHAR(255)")->default("/public/img/profile.png")
        );
    }

    protected static function fetchPermissions() {
        // TODO: implement
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