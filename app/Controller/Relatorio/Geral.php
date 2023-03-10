<?php

namespace App\Controller\Relatorio;

use Dompdf\Dompdf;
use WilliamCosta\DatabaseManager\Database;
use App\Controller\Admin;
use PDO;

class Geral
{

    /**
     * Method to return relatorio geral
     * @param Request $request
     * @return array
     */
    public static function getGeral($request)
    {
        $dompdf = new Dompdf(['enable_remote' => true]);

        // QUERYS TOTAIS
        $total_clientes = (new Database('proprietarios'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_carros = (new Database('carros'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_revisoes = (new Database('revisoes'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // QUERY QTD - QUAL SEXO POSSUI MAIS CARROS
        $qtd_carros_homens = Admin\Home::getQtdCarrosHomens();
        $qtd_carros_mulheres = Admin\Home::getQtdCarrosMulheres();
        $sexo_mais_carros = ($qtd_carros_homens >  $qtd_carros_mulheres) ? 'Homens' : 'Mulheres';
        $sexo_mais_carros = ($qtd_carros_homens ==  $qtd_carros_mulheres) ? 'Ambos' : $sexo_mais_carros;
        $frase_condiocional = ($sexo_mais_carros == 'Homens' || $sexo_mais_carros == 'Mulheres') ? 'possuem mais carros' : 'os sexos possuem a mesma quantidade de carros';

        // QUERY MARCA MAIS UTILIZADA
        $obMarca_mais_utilizada = (new Database('marcas'))->select(null, 'qtd_total DESC', '1')->fetchObject();

        // QUERY MARCA COM MAIS REVISOES
        $query_marca_com_mais_revisoes = 'SELECT m.nome as marca, COUNT(r.id) as qtd FROM marcas m INNER JOIN carros c ON m.id = c.id_marca INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY m.nome ORDER BY qtd DESC LIMIT 1;';
        $resultados_marca_com_mais_revisoes = (new Database)->execute($query_marca_com_mais_revisoes);
        while ($linha = $resultados_marca_com_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $marca_com_mais_revisoes = [
                'marca' => $linha['marca'],
                'qtd' => $linha['qtd']
            ];
        }

        // QUERY PESSOA COM MAIS REVISOES
        $query_pessoa_mais_revisoes = 'SELECT p.id, p.name as name, COUNT(r.id) as qtd FROM proprietarios p INNER JOIN carros c ON p.id = c.id_proprietario INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY p.id ORDER BY qtd DESC LIMIT 1';
        $resultados_pessoa_mais_revisoes = (new Database)->execute($query_pessoa_mais_revisoes);
        while ($linha = $resultados_pessoa_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $pessoa_mais_revisoes  = [
                'name' => $linha['name'],
                'qtd' => $linha['qtd']
            ];
        }

        $dados = '<!DOCTYPE html>
        <head>
            <meta charset="UTF-8">
            <title>Simpllis - Relatório completo</title>
            <link rel="stylesheet" href="http://localhost/teste-controle-revisao-carros/resources/assets/css/relatorio.css">
        </head>
        <body>
        
            <h1 class="txt-1">Relatório completo - Simpllis</h1>

            <h3>Total de clientes registrados : ' . $total_clientes . '</h3>
            <br>
            <h3>' . $sexo_mais_carros . ' ' . $frase_condiocional . '</h3>
            <br>
            <h3>Total de carros registrados : ' . $total_carros . '</h3>
            <br>
            <h3>Total de revisões registradas : ' . $total_revisoes . '</h3>
            <br>
            <h3>Marca mais utilizada : ' . $obMarca_mais_utilizada->nome . '</h3>
            <h3>Marca com mais revisões : ' . $marca_com_mais_revisoes['marca'] . ' | Quantidade de revisões feitas : ' . $marca_com_mais_revisoes['qtd'] . '</h3>
            <h3>Cliente com mais revisões : ' . $pessoa_mais_revisoes['name'] . ' | Quantidade de revisões feitas : ' . $pessoa_mais_revisoes['qtd'] . '</h3>

        </body>
        </html>';


        $dompdf->loadHtml($dados);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream();
    }

    /**
     * Method to return relatorio geral
     * @param Request $request
     * @return array
     */
    public static function getClientes($request)
    {
        $dompdf = new Dompdf(['enable_remote' => true]);

        // QUERYS TOTAIS
        $total_clientes = (new Database('proprietarios'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_homens = (new Database('proprietarios'))->select('sexo = 1', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $total_mulheres = (new Database('proprietarios'))->select('sexo = 2', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // QUERY QTD - QUAL SEXO POSSUI MAIS CARROS
        $qtd_carros_homens = Admin\Home::getQtdCarrosHomens();
        $qtd_carros_mulheres = Admin\Home::getQtdCarrosMulheres();
        $sexo_mais_carros = ($qtd_carros_homens >  $qtd_carros_mulheres) ? 'Homens' : 'Mulheres';
        $sexo_mais_carros = ($qtd_carros_homens ==  $qtd_carros_mulheres) ? 'Ambos' : $sexo_mais_carros;
        $frase_condiocional = ($sexo_mais_carros == 'Homens' || $sexo_mais_carros == 'Mulheres') ? 'possuem mais carros' : 'os sexos possuem a mesma quantidade de carros';

        // QUERY PESSOA COM MAIS REVISOES
        $query_pessoa_mais_revisoes = 'SELECT p.id, p.name as name, COUNT(r.id) as qtd FROM proprietarios p INNER JOIN carros c ON p.id = c.id_proprietario INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY p.id ORDER BY qtd DESC LIMIT 1';
        $resultados_pessoa_mais_revisoes = (new Database)->execute($query_pessoa_mais_revisoes);
        while ($linha = $resultados_pessoa_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $pessoa_mais_revisoes  = [
                'name' => $linha['name'],
                'qtd' => $linha['qtd']
            ];
        }

        // MEDIA IDADE GERAL
        $clientes = (new Database('proprietarios'))->select();
        $qtd_clientes = (new Database('proprietarios'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $media_geral = 0;

        if ($qtd_clientes > 0) {
            $acumulador = 0;
            foreach ($clientes as $key => $value) {
                $acumulador += $value['idade'];
            }
            $media_geral = $acumulador / $qtd_clientes;
        }

        // MEDIA IDADE HOMENS
        $homens = (new Database('proprietarios'))->select('sexo = "' . 1 . '"');
        $qtd_homens = (new Database('proprietarios'))->select('sexo = "' . 1 . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $media_homens = 0;

        if ($qtd_homens > 0) {
            $acumulador = 0;
            foreach ($homens as $key => $value) {
                $acumulador += $value['idade'];
            }
            $media_homens = $acumulador / $qtd_homens;
        }

        // MEDIA IDADE MULHERES
        $mulheres = (new Database('proprietarios'))->select('sexo = "' . 2 . '"');
        $qtd_mulheres = (new Database('proprietarios'))->select('sexo = "' . 2 . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $media_mulheres = 0;
        if ($qtd_mulheres > 0) {
            $acumulador_mulheres = 0;
            foreach ($mulheres as $key => $value) {
                $acumulador_mulheres += $value['idade'];
            }
            $media_mulheres = $acumulador_mulheres / $qtd_mulheres;
        }

        $dados = '<!DOCTYPE html>
        <head>
            <meta charset="UTF-8">
            <title>Simpllis - Relatório Clientes</title>
            <link rel="stylesheet" href="http://localhost/teste-controle-revisao-carros/resources/assets/css/relatorio.css">
        </head>
        <body>
        
            <h1 class="txt-1">Relatório de clientes - Simpllis</h1>
            <br>
            <h3>Total de clientes registrados : ' . $total_clientes . '</h3>
            <h3>Média de idade geral : ' . $media_geral . '</h3>
            <br>
            <h3>Total de homens : ' . $total_homens . ' | Média de idade : ' . $media_homens . ' </h3>
            <br>
            <h3>Total de mulheres : ' . $total_mulheres . ' | Média de idade : ' . $media_mulheres . ' </h3>
            <br>
            <h3>' . $sexo_mais_carros . ' ' . $frase_condiocional . '</h3>
            <br>
            <h3>Cliente com mais revisões : ' . $pessoa_mais_revisoes['name'] . ' | Quantidade de revisões feitas : ' . $pessoa_mais_revisoes['qtd'] . '</h3>

        </body>
        </html>';


        $dompdf->loadHtml($dados);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream();
    }


    /**
     * Method to return relatorio geral
     * @param Request $request
     * @return array
     */
    public static function getCarros($request)
    {
        $dompdf = new Dompdf(['enable_remote' => true]);

        $total_carros = (new Database('carros'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // QUERY QTD - QUAL SEXO POSSUI MAIS CARROS
        $qtd_carros_homens = Admin\Home::getQtdCarrosHomens();
        $qtd_carros_mulheres = Admin\Home::getQtdCarrosMulheres();
        $sexo_mais_carros = ($qtd_carros_homens >  $qtd_carros_mulheres) ? 'Homens' : 'Mulheres';
        $sexo_mais_carros = ($qtd_carros_homens ==  $qtd_carros_mulheres) ? 'Ambos' : $sexo_mais_carros;
        $frase_condiocional = ($sexo_mais_carros == 'Homens' || $sexo_mais_carros == 'Mulheres') ? 'possuem mais carros' : 'os sexos possuem a mesma quantidade de carros';

        // QUERY MARCA MAIS UTILIZADA
        $obMarca_mais_utilizada = (new Database('marcas'))->select(null, 'qtd_total DESC', '1')->fetchObject();

        // QUERY MARCA MAIS UTILIZADA
        $obMarca_mais_utilizada_homem = (new Database('marcas'))->select('qtd_homem > 0', 'qtd_homem DESC', '1')->fetchObject();

        // QUERY MARCA MAIS UTILIZADA
        $obMarca_mais_utilizada_mulher = (new Database('marcas'))->select('qtd_mulher > 0', 'qtd_mulher DESC', '1')->fetchObject();

        // QUERY MARCA COM MAIS REVISOES
        $query_marca_com_mais_revisoes = 'SELECT m.nome as marca, COUNT(r.id) as qtd FROM marcas m INNER JOIN carros c ON m.id = c.id_marca INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY m.nome ORDER BY qtd DESC LIMIT 1;';
        $resultados_marca_com_mais_revisoes = (new Database)->execute($query_marca_com_mais_revisoes);
        while ($linha = $resultados_marca_com_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $marca_com_mais_revisoes = [
                'marca' => $linha['marca'],
                'qtd' => $linha['qtd']
            ];
        }

        $dados = '<!DOCTYPE html>
        <head>
            <meta charset="UTF-8">
            <title>Simpllis - Relatório Carros</title>
            <link rel="stylesheet" href="http://localhost/teste-controle-revisao-carros/resources/assets/css/relatorio.css">
        </head>
        <body>
        
            <h1 class="txt-1">Relatório de carros - Simpllis</h1>
            <br>
            <h3>Total de carros registrados : ' . $total_carros . '</h3>
            <br>
            <h3>' . $sexo_mais_carros . ' ' . $frase_condiocional . '</h3>
            <br>
            <h3>Marca mais utilizada : ' . $obMarca_mais_utilizada->nome . '</h3>
            <h3>Marca mais utilizada por homens : ' . $obMarca_mais_utilizada_homem->nome . '</h3>
            <h3>Marca mais utilizada por mulheres : ' . $obMarca_mais_utilizada_mulher->nome . '</h3>
            <h3>Marca com mais revisões : ' . $marca_com_mais_revisoes['marca'] . ' | Quantidade de revisões feitas : ' . $marca_com_mais_revisoes['qtd'] . '</h3>

        </body>
        </html>';


        $dompdf->loadHtml($dados);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream();
    }

    /**
     * Method to return relatorio geral
     * @param Request $request
     * @return array
     */
    public static function getRevisoes($request)
    {
        $dompdf = new Dompdf(['enable_remote' => true]);

        $total_revisoes = (new Database('revisoes'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // QUERY MARCA COM MAIS REVISOES
        $query_marca_com_mais_revisoes = 'SELECT m.nome as marca, COUNT(r.id) as qtd FROM marcas m INNER JOIN carros c ON m.id = c.id_marca INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY m.nome ORDER BY qtd DESC LIMIT 1;';
        $resultados_marca_com_mais_revisoes = (new Database)->execute($query_marca_com_mais_revisoes);
        while ($linha = $resultados_marca_com_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $marca_com_mais_revisoes = [
                'marca' => $linha['marca'],
                'qtd' => $linha['qtd']
            ];
        }

        // QUERY PESSOA COM MAIS REVISOES
        $query_pessoa_mais_revisoes = 'SELECT p.id, p.name as name, COUNT(r.id) as qtd FROM proprietarios p INNER JOIN carros c ON p.id = c.id_proprietario INNER JOIN revisoes r ON c.id = r.id_carro GROUP BY p.id ORDER BY qtd DESC LIMIT 1';
        $resultados_pessoa_mais_revisoes = (new Database)->execute($query_pessoa_mais_revisoes);
        while ($linha = $resultados_pessoa_mais_revisoes->fetch(PDO::FETCH_ASSOC)) {
            $pessoa_mais_revisoes  = [
                'name' => $linha['name'],
                'qtd' => $linha['qtd']
            ];
        }

        $dados = '<!DOCTYPE html>
        <head>
            <meta charset="UTF-8">
            <title>Simpllis - Relatório Carros</title>
            <link rel="stylesheet" href="http://localhost/teste-controle-revisao-carros/resources/assets/css/relatorio.css">
        </head>
        <body>
        
            <h1 class="txt-1">Relatório de carros - Simpllis</h1>
            <br>
            <h3>Total de revisões registradas : ' . $total_revisoes . '</h3>
            <br>
            <h3>Marca com mais revisões : ' . $marca_com_mais_revisoes['marca'] . ' | Quantidade de revisões feitas : ' . $marca_com_mais_revisoes['qtd'] . '</h3>
            <h3>Cliente com mais revisões : ' . $pessoa_mais_revisoes['name'] . ' | Quantidade de revisões feitas : ' . $pessoa_mais_revisoes['qtd'] . '</h3>
            
        </body>
        </html>';


        $dompdf->loadHtml($dados);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream();
    }
}
