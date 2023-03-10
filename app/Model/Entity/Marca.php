<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Marca
{

    /**
     * Admin user id
     * @var integer
     */
    public $id;

    /**
     * Marca
     * @var string
     */
    public $nome;

    /**
     * Quantidade de registros - homem
     * @var int
     */
    public $qtd_homem;

    /**
     * Quantidade de registros - mulher
     * @var int
     */
    public $qtd_mulher;

    /**
     * Quantidade de registros - total
     * @var int
     */
    public $qtd_total;


    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getMarcaById($id)
    {
        return (new Database('marcas'))->select('id = "' . $id . '"')->fetchObject(self::class);
    }

    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getMarcaByName($name)
    {
        return (new Database('marcas'))->select('nome = "' . $name . '"')->fetchObject(self::class);
    }

    /**
     * Register Account method
     */
    public function register()
    {
        $this->id = (new Database('marcas'))->insert([
            'nome' => $this->nome,
            'qtd_homem' => $this->qtd_homem,
            'qtd_mulher' => $this->qtd_mulher,
            'qtd_total' => $this->qtd_total,
        ]);

        return [
            'true',
            'id' => $this->id,
        ];
    }

    public function updateQtd()
    {
        $values = [
            'qtd_homem' => $this->qtd_homem,
            'qtd_mulher' => $this->qtd_mulher,
            'qtd_total' => $this->qtd_total,
        ];
        return (new Database('marcas'))->update('id = ' . $this->id, $values);
    }

    /**
     * Method to delete in db with the actual instance
     */
    public function delete()
    {
        return (new Database('marcas'))->delete('id = ' . $this->id);
    }
}
