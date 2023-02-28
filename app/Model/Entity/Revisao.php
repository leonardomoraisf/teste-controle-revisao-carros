<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Revisao
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
    public $id_carro;

    /**
     * Id proprietarios
     * @var int
     */
    public $status;

    /**
     * Id proprietarios
     * @var string
     */
    public $data;

    /**
     * Id proprietarios
     * @var int
     */
    public $tipo;

    /**
     * Detalhes
     * @var string
     */
    public $detalhes;

    /**
     * Marcas
     * @var array
     */
    public static $tipos = [
        '1' => '20 mil km',
        '2' => '30 mil km',
        '3' => '40 mil km',
        '4' => '50 mil km',
        '5' => '60 mil km',
        '6' => '70 mil km',
        '7' => '80 mil km',
        '8' => '90 mil km',
        '9' => '100 mil km',
    ];

    public static function getTypes()
    {
        return self::$tipos;
    }

    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getRevisaoById($id)
    {
        return (new Database('revisoes'))->select('id = "' . $id . '"')->fetchObject(self::class);
    }

    /**
     * Register Account method
     */
    public function register()
    {
        $this->id = (new Database('revisoes'))->insert([
            'id_carro' => $this->id_carro,
            'status' => $this->status,
            'data' => $this->data,
            'tipo' => $this->tipo,
            'detalhes' => $this->detalhes,
        ]);

        return [
            'true',
            'id' => $this->id,
        ];
    }

    public function update()
    {
        return (new Database('revisoes'))->update('id = ' . $this->id, [
            'id_carro' => $this->id_carro,
            'status' => $this->status,
            'data' => $this->data,
            'tipo' => $this->tipo,
            'detalhes' => $this->detalhes,
        ]);
    }

    /**
     * Method to delete in db with the actual instance
     */
    public function delete()
    {
        return (new Database('revisoes'))->delete('id = ' . $this->id);
    }
}