<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Carro
{

    /**
     * Admin user id
     * @var integer
     */
    public $id;

    /**
     * Marca
     * @var int
     */
    public $id_marca;

    /**
     * Id proprietarios
     * @var int
     */
    public $id_proprietario;

    /**
     * Ultima revisao
     * @var string
     */
    public $ultima_revisao;


    /**
     * Method to return an user using the username
     * @param string $id
     * @return User
     */
    public static function getCarroById($id)
    {
        return (new Database('carros'))->select('id = "' . $id . '"')->fetchObject(self::class);
    }

    /**
     * Register Account method
     */
    public function register()
    {
        $this->id = (new Database('carros'))->insert([
            'id_marca' => $this->id_marca,
            'id_proprietario' => $this->id_proprietario,
            'ultima_revisao' => $this->ultima_revisao,
        ]);

        return [
            'true',
            'id' => $this->id,
        ];
    }

    public function update()
    {
        $values = [
            'id_marca' => $this->id_marca,
            'id_proprietario' => $this->id_proprietario,
            'ultima_revisao' => $this->ultima_revisao,
        ];
        return (new Database('carros'))->update('id = ' . $this->id, $values);
    }

    /**
     * Method to delete in db with the actual instance
     */
    public function delete($obCliente)
    {
        $obMarca = Marca::getMarcaById($this->id_marca);
        $obMarca->updateQtd($obMarca->qtd_total - 1);

        if($obCliente->sexo == 1)
            $obMarca->updateQtd($obMarca->qtd_homem - 1);
        
        if($obCliente->sexo == 2)
            $obMarca->updateQtd($obMarca->qtd_mulher - 1);

        return (new Database('carros'))->delete('id = ' . $this->id);
    }
}
