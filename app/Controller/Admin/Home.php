<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use App\Model\Entity\Proprietario as Cliente;
use PDO;

class Home extends Page
{
    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getQtdCarrosHomens()
    {
        $results = (new Database('proprietarios'))->select('sexo = "' . 1 . '"');

        $acumulador = 0;
        // RENDER ITEM
        while ($obCliente = $results->fetchObject(Cliente::class)) {
            // TOTAL CAR REGS
            $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
            $acumulador += $total_carros_registrados;
        }

        return $acumulador;
    }

    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getQtdCarrosMulheres()
    {
        $results = (new Database('proprietarios'))->select('sexo = "' . 2 . '"');

        $acumulador = 0;
        // RENDER ITEM
        while ($obCliente = $results->fetchObject(Cliente::class)) {
            // TOTAL CAR REGS
            $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
            $acumulador += $total_carros_registrados;
        }

        return $acumulador;
    }

    public static function getPessoasMaisRevisoesItems($request)
    {
        $itens = '';

        $query = 'SELECT p.id, p.name as name, COUNT(r.id) as qtd FROM proprietarios p INNER JOIN carros c ON p.id = c.id_proprietario INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY p.id ORDER BY qtd DESC LIMIT 4';

        $results = (new Database)->execute($query);

        // RENDER ITEM
        while ($obCliente = $results->fetch(PDO::FETCH_OBJ)) {
            $itens .= View::render('views/admin/includes/home/box_pessoas_mais_revisoes/item', [
                'id' => $obCliente->id,
                'name' => $obCliente->name,
                'qtd' => $obCliente->qtd,
            ]);
        }
        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getHome($request)
    {
        $total_clientes = (new Database('proprietarios'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_carros = (new Database('carros'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_revisoes = (new Database('revisoes'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $qtd_carros_homens = self::getQtdCarrosHomens();
        $qtd_carros_mulheres = self::getQtdCarrosMulheres();
        $sexo_mais_carros = ($qtd_carros_homens >  $qtd_carros_mulheres) ? 'Homens' : 'Mulheres';
        $sexo_mais_carros = ($qtd_carros_homens ==  $qtd_carros_mulheres) ? 'Ambos' : $sexo_mais_carros;
        $frase_condiocional = ($sexo_mais_carros == 'Homens' || $sexo_mais_carros == 'Mulheres') ? 'Possuem mais carros' : 'Possuem a mesma quantidade de carros';

        $elements = parent::getElements();
        return View::render('views/admin/home', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Dashboard',
            'user_name' => $_SESSION['user']['name'],

            'box_total_clientes' => View::render('views/admin/includes/home/box_total_clientes'),
            'total_clientes' => $total_clientes,

            'box_total_carros' => View::render('views/admin/includes/home/box_total_carros'),
            'total_carros' => $total_carros,

            'box_total_revisoes' => View::render('views/admin/includes/home/box_total_revisoes'),
            'total_revisoes' => $total_revisoes,

            'box_sexo_mais_carros' => View::render('views/admin/includes/home/box_sexo_mais_carros'),
            'sexo_mais_carros' => $sexo_mais_carros,
            'frase_condicional' => $frase_condiocional,

            'chartjs_marcas_mais_utilizadas' => View::render('views/admin/includes/home/chartjs_marcas_mais_utilizadas'),
            'chartjs_marcas_mais_revisoes' => View::render('views/admin/includes/home/chartjs_marcas_mais_revisoes'),
            'box_pessoas_mais_revisoes' => View::render('views/admin/includes/home/box_pessoas_mais_revisoes', [
                'itens' => self::getPessoasMaisRevisoesItems($request),
            ]),
        ]);
    }
}
