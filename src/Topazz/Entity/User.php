<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Database\Entity;
use Topazz\Database\Query;
use Topazz\Database\Table;
use Topazz\Database\TableBuilder;

class User extends Entity {

    const ROLE_ADMIN = 0;
    const ROLE_MODERATOR = 1;
    const ROLE_EDITOR = 2;
    const ROLE_BLOGGER = 3;

    public $username;
    public $email;
    public $role = self::ROLE_BLOGGER;
    public $first_name;
    public $last_name;
    public $profile_picture = "/public/img/profile.png";
    private $password;

    public function setPassword(string $password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function matchPassword(string $password) {
        return password_verify($password, $this->password);
    }

    public function save() {
        if (is_null($this->id)) {
            $this->id = $this->db->query(
                Query::create(
                    "INSERT INTO users " .
                    "(username, email, role, first_name, last_name, password) " .
                    "VALUES (?, ?, ?, ?, ?, ?)"
                )->setAttributes([
                    $this->username,
                    $this->email,
                    $this->role,
                    $this->first_name,
                    $this->last_name,
                    $this->password
                ])
            )->run(self::class)->insertedId();
        } else {
            $this->db->query(
                Query::create(
                    "UPDATE users " .
                    "SET email = ?, role = ?, first_name = ?, last_name = ?, password = ? " .
                    "WHERE id = ?"
                )->setAttributes([
                    $this->email,
                    $this->role,
                    $this->first_name,
                    $this->last_name,
                    $this->password
                ])->addAttribute($this->id)
            )->run(self::class);
        }
    }

    public static function getTable(): Table {
        return (new TableBuilder("users"))
            ->serial("id")
            ->varchar("username", 40)->notNull()->unique()
            ->varchar("email", 50)->notNull()
            ->varchar("password", 50)->notNull()
            ->integer("role", TableBuilder::SMALLINT)->notNull()->unsigned()->default(User::ROLE_BLOGGER)
            ->varchar("first_name", 50)
            ->varchar("last_name", 50)
            ->create();
    }
}