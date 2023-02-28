<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class UserOficina
{

    /**
     * Admin user id
     * @var integer
     */
    public $id;

    /**
     * Email
     * @var string
     */
    public $email;

    /**
     * User password
     * @var string
     */
    public $password;

    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * Method to return an user using the email
     * @param string $email
     * @return UserOficina
     */
    public static function getUserByEmail($email)
    {
        return (new Database('`usuarios_oficina`'))->select('email = "' . $email . '"')->fetchObject(self::class);
    }

    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getUserById($id)
    {
        return (new Database('`usuarios_oficina`'))->select('id = "' . $id . '"')->fetchObject(self::class);
    }

    /**
     * Register Account method
     */
    public function register()
    {
        $this->id = (new Database('`usuarios_oficina`'))->insert([
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ]);

        return true;
    }

    public function update()
    {
        return (new Database('`usuarios_oficina`'))->update('id = ' . $this->id, [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ]);
    }

    /**
     * Method to delete in db with the actual instance
     */
    public function delete()
    {
        return (new Database('`usuarios_oficina`'))->delete('id = ' . $this->id);
    }
}
