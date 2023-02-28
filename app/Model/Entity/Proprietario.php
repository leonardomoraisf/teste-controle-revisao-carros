<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Proprietario
{

    /**
     * Admin user id
     * @var integer
     */
    public $id;

    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * Idade
     * @var int
     */
    public $idade;

    /**
     * Sexo
     * @var int
     */
    public $sexo;

    /**
     * Sexos
     * @var array
     */
    public static $sexos = [
        '1' => 'Homem',
        '2' => 'Mulher',
    ];

    public static function getSexos()
    {
        return self::$sexos;
    }

    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getUserById($id)
    {
        return (new Database('proprietarios'))->select('id = "' . $id . '"')->fetchObject(self::class);
    }

    /**
     * Register Account method
     */
    public function register()
    {
        $this->id = (new Database('`proprietarios`'))->insert([
            'name' => $this->name,
            'sexo' => $this->sexo,
            'idade' => $this->idade
        ]);

        return [
            'true',
            'id' => $this->id,
        ];
    }

    public function update()
    {
        return (new Database('`proprietarios`'))->update('id = ' . $this->id, [
            'name' => $this->name,
            'sexo' => $this->sexo,
            'idade' => $this->idade
        ]);
    }

    /**
     * Method to delete in db with the actual instance
     */
    public function delete()
    {
        return (new Database('`proprietarios`'))->delete('id = ' . $this->id);
    }
}
