<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Gravatar\Gravatar;
use function in_array;
use Topazz\Application;
use Topazz\Container;
use Topazz\Database\Statement\Statement;
use Topazz\Environment;
use Topazz\Service\Security;
use Topazz\TopazzApplicationException;

class User extends Entity {

    const ROLE_ADMIN = 0;
    const ROLE_MODERATOR = 1;
    const ROLE_EDITOR = 2;
    const ROLE_BLOGGER = 3;

    protected static $table = "users";

    protected $config;

    public $uuid;
    public $username;
    public $email;
    public $role = self::ROLE_BLOGGER;
    public $permissions = [];
    public $first_name;
    public $last_name;
    public $profile_picture = "/public/img/picture.png";
    private $password;

    public function __construct() {
        parent::__construct();
        $this->config = $this->container->config;

        $allPermissions = $this->config->get('user.permissions');
        if ($this->role === self::ROLE_ADMIN) {
            $this->permissions = $this->config->get('user.available_permissions');
        } else {
            $this->permissions = $allPermissions[$this->role];
        }
    }

    public function setPassword(string $password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function matchPassword(string $password) {
        return password_verify($password, $this->password);
    }

    public function hasPermission(string $permission): bool {
        if (!$this->isValidPermission($permission)) {
            throw new TopazzApplicationException("This is not any valid user permission");
        }
        if ($this->role == self::ROLE_ADMIN) {
            return true;
        }
        return in_array($permission, $this->permissions);
    }

    private function isValidPermission(string $permission): bool {
        return in_array($permission, $this->config->get('user.available_permissions'));
    }

    protected function create() {
        $this->id = Statement::insert(
            'uuid',
            'username',
            'email',
            'password',
            'role',
            'first_name',
            'last_name',
            'profile_picture'
        )->into('users')->values(
            uniqid(),
            $this->username,
            $this->email,
            $this->password,
            $this->role,
            $this->first_name,
            $this->last_name,
            $this->profile_picture
        )->prepare()->execute()->lastInsertedId();
    }

    protected function update() {
        Statement::update('users')
            ->set('username', $this->username)
            ->set('email', $this->email)
            ->set('password', $this->password)
            ->set('role', $this->role)
            ->set('first_name', $this->first_name)
            ->set('last_name', $this->last_name)
            ->set('profile_picture', $this->profile_picture)
            ->where('id', $this->id)
            ->prepare()->execute();
    }
}