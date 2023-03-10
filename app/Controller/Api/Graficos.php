<?php

namespace App\Controller\Api;

use WilliamCosta\DatabaseManager\Database;
use App\Model\Entity\Marca;
use PDO;

class Graficos extends Api
{

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasItems($request)
    {
        // marcas
        $itens = [];

        $results = (new Database('marcas'))->select(null, 'qtd_total DESC');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            if($obMarca->qtd_total == 0){
                continue;
            }
            $itens[] = [
                'nome' => $obMarca->nome,
                'qtd' => $obMarca->qtd_total,
            ];
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getMarcasMaisUtilizadas($request)
    {

        return self::getMarcasItems($request);
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasHomemItems($request)
    {
        // marcas
        $itens = [];

        $results = (new Database('marcas'))->select(null, 'qtd_total DESC');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens[] = [
                'nome' => $obMarca->nome,
                'qtd' => $obMarca->qtd_total,
            ];
        }

        return $itens;
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasMulherItems($request)
    {
        // marcas
        $itens = [];

        $results = (new Database('marcas'))->select(null, 'qtd_total DESC');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens[] = [
                'nome' => $obMarca->nome,
                'qtd' => $obMarca->qtd_total,
            ];
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getMarcasMaisUtilizadasSexo($request)
    {
        return [
            'homem'  => self::getMarcasHomemItems($request),
            'mulher' => self::getMarcasMulherItems($request),
        ];
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getMarcasMaisRevisoesItems($request)
    {
        // marcas
        $itens = [];

        $query = 'SELECT m.nome as marca, COUNT(r.id) as qtd FROM marcas m INNER JOIN carros c ON m.id = c.id_marca INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY m.nome ORDER BY qtd DESC;';
        
        $revisoes = (new Database)->execute($query);
        
        while ($linha = $revisoes->fetch(PDO::FETCH_OBJ)){
            $novaLinha = [
                'marca' => $linha->marca,
                'qtd' => $linha->qtd
            ];
            array_push($itens,$novaLinha);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getMarcasMaisRevisoes($request)
    {
        return self::getMarcasMaisRevisoesItems($request);
    }
}
