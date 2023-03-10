<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\Pagination;
use App\Model\Entity\Proprietario as Cliente;
use App\Model\Entity\Marca;
use App\Model\Entity\Carro;
use PDO;

class Clientes extends Page
{

    /**
     * Method to return status view
     * @param Request $request
     * @return string
     */
    public static function getStatus($request)
    {
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // STATUS
        if (!isset($queryParams['status'])) return '';

        // STATUS MESSAGES
        switch ($queryParams['status']) {
            case 'erro':
                return Alert::getError("Não existe esse cliente!");
                break;
            case 'deletado':
                return Alert::getSuccess("Cliente totalmente deletado!");
                break;
        }
    }

    /**
     * Method to catch sexos render
     * @param Request $request
     * @return string
     */
    private static function getSexosItems($request)
    {
        // Sexos
        $itens = '';

        $results = Cliente::getSexos();

        foreach ($results as $key => $value) {
            $itens .= View::render('views/admin/includes/cliente/item', [
                'sexo_value' => $key,
                'sexo_name' => $value,
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getFormCliente($request, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $sexos = self::getSexosItems($request);

        $elements = parent::getElements();
        return View::render('views/admin/forms/forms_cliente', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Novo Cliente',
            'user_name' => $_SESSION['user']['name'],
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'sexos' => $sexos,
            'active_novo_cliente' => 'active'
        ]);
    }

    public static function setFormCliente($request)
    {

        $postVars = $request->getPostVars();

        $name = $postVars['name'];
        $years = $postVars['idade'];
        $sexo = $postVars['sexo'];

        if ($name == '' || $years == '' || $sexo == '') {
            return self::getFormCliente($request, 'Há campos nulos!');
        }

        if ($years < 0) {
            return self::getFormCliente($request, 'A idade não pode ser menor que 0');
        }

        $obCliente = new Cliente;
        $obCliente->name = $name;
        $obCliente->idade = $years;
        $obCliente->sexo = $sexo;
        $obCliente->register();

        // REDIRECT
        $request->getRouter()->redirect('/dashboard/forms/' . $obCliente->id . '/carro');
    }

    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getGeralClientesItems($request, &$obPagination)
    {
        $itens = '';

        // TOTAL REG QUANTITY
        $totalQuantity = (new Database('proprietarios'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // ACTUAL PAGE
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        // PAGINATION INSTANCE
        $obPagination = new Pagination($totalQuantity, $actualPage, 10);

        if (!isset($queryParams['f'])) {
            // RESULTS
            $results = (new Database('proprietarios'))->select(null, 'id ASC', $obPagination->getLimit());

            // RENDER ITEM
            while ($obCliente = $results->fetchObject(Cliente::class)) {
                // TOTAL CAR REGS
                $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

                $query_media_tempo = 'SELECT AVG(DATEDIFF(r1.data, r2.data)) AS media_tempo_revisao
                FROM (
                  SELECT c.id_proprietario, MAX(r.data) AS data
                  FROM carros c
                  LEFT JOIN revisoes r ON c.id = r.id_carro
                  WHERE c.id_proprietario = "' . $obCliente->id . '"
                  GROUP BY c.id_proprietario, c.id
                ) AS carros_revisoes
                JOIN revisoes r1 ON carros_revisoes.data = r1.data
                LEFT JOIN revisoes r2 ON r1.id_carro = r2.id_carro AND r2.data < r1.data
                WHERE r2.id IS NOT NULL;';


                $media_tempo_revisao = $query_media_tempo;

                $resultados_media_tempo = (new Database)->execute($media_tempo_revisao);

                while ($linha = $resultados_media_tempo->fetch(PDO::FETCH_OBJ)) {

                    $query_previsao = 'SELECT 
                    c.id_proprietario,
                    AVG(DATEDIFF(r2.data, r1.data)) as media_dias_revisao,
                    MAX(r1.data) as ultima_revisao,
                    DATE_ADD(MAX(r1.data), INTERVAL AVG(DATEDIFF(r2.data, r1.data)) DAY) as previsao_proxima_revisao
                    FROM carros c
                    LEFT JOIN revisoes r1 ON c.id = r1.id_carro
                    LEFT JOIN revisoes r2 ON c.id = r2.id_carro AND r2.data > r1.data
                    WHERE c.id_proprietario = "' . $obCliente->id . '"
                    GROUP BY c.id_proprietario;';

                    $resultados_previsao = (new Database)->execute($query_previsao);

                    while ($linha_previsao = $resultados_previsao->fetch(PDO::FETCH_OBJ)) {
                        $itens .= View::render('views/admin/includes/cliente/table_geral/table_item_geral', [
                            'nome_cliente' => $obCliente->name,
                            'idade_cliente' => $obCliente->idade,
                            'sexo_cliente' => Cliente::$sexos[$obCliente->sexo],
                            'carros_registrados' => $total_carros_registrados,
                            'id_cliente' => $obCliente->id,
                            'media_tempo_revisao' => (int)$linha->media_tempo_revisao . ' dias',
                            'previsao_proxima_revisao' => ($total_carros_registrados == 1 || $total_carros_registrados == 0) ? 'Sem previsão' : date('d/m/Y', strtotime($linha_previsao->previsao_proxima_revisao)),
                        ]);
                    }
                }
            }

            return $itens;
        }
    }


    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getSexoHomemClientesItems($request)
    {
        $queryParams = $request->getQueryParams();

        $itens = '';

        if (isset($queryParams['f']) && $queryParams['f'] == 'sexo') {
            // RESULTS
            $results = (new Database('proprietarios'))->select('sexo = "' . 1 . '"', 'id ASC');

            // RENDER ITEM
            while ($obCliente = $results->fetchObject(Cliente::class)) {
                // TOTAL CAR REGS
                $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

                $query_media_tempo = 'SELECT AVG(DATEDIFF(r1.data, r2.data)) AS media_tempo_revisao
                FROM (
                  SELECT c.id_proprietario, MAX(r.data) AS data
                  FROM carros c
                  LEFT JOIN revisoes r ON c.id = r.id_carro
                  WHERE c.id_proprietario = "' . $obCliente->id . '"
                  GROUP BY c.id_proprietario, c.id
                ) AS carros_revisoes
                JOIN revisoes r1 ON carros_revisoes.data = r1.data
                LEFT JOIN revisoes r2 ON r1.id_carro = r2.id_carro AND r2.data < r1.data
                WHERE r2.id IS NOT NULL;';

                $media_tempo_revisao = $query_media_tempo;

                $resultados_media_tempo = (new Database)->execute($media_tempo_revisao);

                while ($linha = $resultados_media_tempo->fetch(PDO::FETCH_OBJ)) {

                    $query_previsao = 'SELECT 
                    c.id_proprietario,
                    AVG(DATEDIFF(r2.data, r1.data)) as media_dias_revisao,
                    MAX(r1.data) as ultima_revisao,
                    DATE_ADD(MAX(r1.data), INTERVAL AVG(DATEDIFF(r2.data, r1.data)) DAY) as previsao_proxima_revisao
                    FROM carros c
                    LEFT JOIN revisoes r1 ON c.id = r1.id_carro
                    LEFT JOIN revisoes r2 ON c.id = r2.id_carro AND r2.data > r1.data
                    WHERE c.id_proprietario = "' . $obCliente->id . '"
                    GROUP BY c.id_proprietario;';

                    $resultados_previsao = (new Database)->execute($query_previsao);

                    while ($linha_previsao = $resultados_previsao->fetch(PDO::FETCH_OBJ)) {
                        $itens .= View::render('views/admin/includes/cliente/table_sexo/table_item_sexo', [
                            'nome_cliente' => $obCliente->name,
                            'idade_cliente' => $obCliente->idade,
                            'sexo_cliente' => Cliente::$sexos[$obCliente->sexo],
                            'carros_registrados' => $total_carros_registrados,
                            'id_cliente' => $obCliente->id,
                            'media_tempo_revisao' => (int)$linha->media_tempo_revisao . ' dias',
                            'previsao_proxima_revisao' => ($total_carros_registrados == 1 || $total_carros_registrados == 0) ? 'Sem previsão' : date('d/m/Y', strtotime($linha_previsao->previsao_proxima_revisao)),
                        ]);
                    }
                }
            }

            return $itens;
        }
    }

    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getSexoMulherClientesItems($request)
    {
        $queryParams = $request->getQueryParams();

        $itens = '';

        if (isset($queryParams['f']) && $queryParams['f'] == 'sexo') {
            // RESULTS
            $results = (new Database('proprietarios'))->select('sexo = "' . 2 . '"', 'id ASC');

            // RENDER ITEM
            while ($obCliente = $results->fetchObject(Cliente::class)) {
                // TOTAL CAR REGS
                $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

                $query_media_tempo = 'SELECT AVG(DATEDIFF(r1.data, r2.data)) AS media_tempo_revisao
                FROM (
                  SELECT c.id_proprietario, MAX(r.data) AS data
                  FROM carros c
                  LEFT JOIN revisoes r ON c.id = r.id_carro
                  WHERE c.id_proprietario = "' . $obCliente->id . '"
                  GROUP BY c.id_proprietario, c.id
                ) AS carros_revisoes
                JOIN revisoes r1 ON carros_revisoes.data = r1.data
                LEFT JOIN revisoes r2 ON r1.id_carro = r2.id_carro AND r2.data < r1.data
                WHERE r2.id IS NOT NULL;';


                $media_tempo_revisao = $query_media_tempo;

                $resultados_media_tempo = (new Database)->execute($media_tempo_revisao);

                while ($linha = $resultados_media_tempo->fetch(PDO::FETCH_OBJ)) {

                    $query_previsao = 'SELECT 
                    c.id_proprietario,
                    AVG(DATEDIFF(r2.data, r1.data)) as media_dias_revisao,
                    MAX(r1.data) as ultima_revisao,
                    DATE_ADD(MAX(r1.data), INTERVAL AVG(DATEDIFF(r2.data, r1.data)) DAY) as previsao_proxima_revisao
                    FROM carros c
                    LEFT JOIN revisoes r1 ON c.id = r1.id_carro
                    LEFT JOIN revisoes r2 ON c.id = r2.id_carro AND r2.data > r1.data
                    WHERE c.id_proprietario = "' . $obCliente->id . '"
                    GROUP BY c.id_proprietario;';

                    $resultados_previsao = (new Database)->execute($query_previsao);

                    while ($linha_previsao = $resultados_previsao->fetch(PDO::FETCH_OBJ)) {
                        $itens .= View::render('views/admin/includes/cliente/table_sexo/table_item_sexo', [
                            'nome_cliente' => $obCliente->name,
                            'idade_cliente' => $obCliente->idade,
                            'sexo_cliente' => Cliente::$sexos[$obCliente->sexo],
                            'carros_registrados' => $total_carros_registrados,
                            'id_cliente' => $obCliente->id,
                            'media_tempo_revisao' => (int)$linha->media_tempo_revisao . ' dias',
                            'previsao_proxima_revisao' => ($total_carros_registrados == 1 || $total_carros_registrados == 0) ? 'Sem previsão' : date('d/m/Y', strtotime($linha_previsao->previsao_proxima_revisao)),
                        ]);
                    }
                }
            }

            return $itens;
        }
    }


    /**
     * Method to return home view
     * @return string
     */
    public static function getClientes($request)
    {
        $queryParams = $request->getQueryParams();

        $active_geral = (!isset($queryParams['f'])) ? 'active' : '';

        if (!isset($queryParams['f'])) {
            $card_table = View::render('views/admin/includes/cliente/table_geral/table_geral', [
                'itens' => self::getGeralClientesItems($request, $obPagination),
            ]);
        }

        $active_sexo = (isset($queryParams['f'])) && $queryParams['f'] == 'sexo' ? 'active' : '';

        if (isset($queryParams['f']) && $queryParams['f'] == 'sexo') {

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

            $card_table = View::render('views/admin/includes/cliente/table_sexo/table_sexo', [
                'itens_homens' => self::getSexoHomemClientesItems($request),
                'itens_mulheres' => self::getSexoMulherClientesItems($request),
                'media_homens' => $media_homens,
                'media_mulheres' => $media_mulheres,
            ]);
        }

        $elements = parent::getElements();
        return View::render('views/admin/clientes/clientes', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Clientes',
            'user_name' => $_SESSION['user']['name'],
            'card_table' => $card_table,
            'pagination' => (isset($obPagination)) ? parent::getPagination($request, $obPagination) : '',
            'active_clientes' => 'active',
            'active_geral' => $active_geral,
            'active_sexo' => $active_sexo,
            'status' => self::getStatus($request),
        ]);
    }

    public static function getClienteCarrosItems($request, $id)
    {
        $itens = '';

        $obCliente = Cliente::getUserById($id);

        // RESULTS
        $results = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"');

        // RENDER ITEM
        while ($obCarro = $results->fetchObject(Carro::class)) {
            $obMarca = Marca::getMarcaById($obCarro->id_marca);
            $itens .= View::render('views/admin/includes/cliente_carros/table_geral/table_item_geral', [
                'nome_marca' => $obMarca->nome,
                'ultima_revisao' => ($obCarro->ultima_revisao == '0000-00-00') ? 'Sem revisão' : date('d/m/Y', strtotime($obCarro->ultima_revisao)),
                'id_carro' => $obCarro->id,
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getClienteCarros($request, $id)
    {

        $obCliente = Cliente::getUserById($id);
        if (!$obCliente instanceof Cliente) {
            $request->getRouter()->redirect('/dashboard/clientes?status=erro');
        }

        // TOTAL CAR REGS
        $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $card_table = ($total_carros_registrados == 0) ? View::render('views/admin/includes/cliente_carros/table_non/item') : View::render('views/admin/includes/cliente_carros/table_geral/table_geral', [
            'itens' => self::getClienteCarrosItems($request, $obCliente->id)
        ]);

        $elements = parent::getElements();
        return View::render('views/admin/clientes/clientes_carros', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Clientes',
            'user_name' => $_SESSION['user']['name'],
            'card_table' => $card_table,
            'pagination' => (isset($obPagination)) ? parent::getPagination($request, $obPagination) : '',
            'nome_cliente' => $obCliente->name,
            'id_cliente' => $obCliente->id,
            'active_clientes' => 'active',
            'status' => self::getStatus($request),
        ]);
    }

    public static function setDelete($request, $id)
    {
        $obCliente = Cliente::getUserById($id);

        if (!$obCliente instanceof Cliente) {
            $request->getRouter()->redirect('/dashboard/clientes?status=erro');
        }

        $qtd_carros = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $c = 0;
        while ($c < $qtd_carros) {
            $obCarro = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"')->fetchObject();

            (new Database('revisoes'))->delete('id_carro = "' . $obCarro->id . '"');

            $obMarca = Marca::getMarcaById($obCarro->id_marca);
            $obMarca->qtd_total -= 1;

            if ($obCliente->sexo == 1)
                $obMarca->qtd_homem -= 1;

            if ($obCliente->sexo == 2)
                $obMarca->qtd_mulher -= 1;

            $obMarca->updateQtd();

            (new Database('carros'))->delete('id = "' . $obCarro->id . '"');

            $c += 1;
        }

        $obCliente->delete();

        $request->getRouter()->redirect('/dashboard/clientes?status=deletado');
    }
}
