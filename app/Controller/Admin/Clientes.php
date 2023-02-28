<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\Pagination;
use App\Model\Entity\Proprietario as Cliente;
use App\Model\Entity\Marca;
use App\Model\Entity\Carro;

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
        // Categories
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
                $itens .= View::render('views/admin/includes/cliente/table_geral/table_item_geral', [
                    'nome_cliente' => $obCliente->name,
                    'idade_cliente' => $obCliente->idade,
                    'sexo_cliente' => Cliente::$sexos[$obCliente->sexo],
                    'carros_registrados' => $total_carros_registrados,
                    'id_cliente' => $obCliente->id,
                ]);
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
    private static function getSexoHomemClientesItems()
    {
        // Categories
        $itens = '';

        $results = (new Database('proprietarios'))->select('sexo = "' . 1 . '"', 'id DESC');

        // RENDER ITEM
        while ($obCliente = $results->fetchObject(Cliente::class)) {
            // TOTAL CAR REGS
            $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
            $itens .= View::render('views/admin/includes/cliente/table_sexo/table_item_sexo', [
                'nome_cliente' => $obCliente->name,
                'idade_cliente' => $obCliente->idade,
                'id_cliente' => $obCliente->id,
                'carros_registrados' => $total_carros_registrados,
            ]);
        }

        return $itens;
    }

    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getSexoMulherClientesItems()
    {
        // Categories
        $itens = '';

        $results = (new Database('proprietarios'))->select('sexo = "' . 2 . '"', 'id DESC');

        // RENDER ITEM
        while ($obCliente = $results->fetchObject(Cliente::class)) {
            // TOTAL CAR REGS
            $total_carros_registrados = (new Database('carros'))->select('id_proprietario = "' . $obCliente->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
            $itens .= View::render('views/admin/includes/cliente/table_sexo/table_item_sexo', [
                'nome_cliente' => $obCliente->name,
                'idade_cliente' => $obCliente->idade,
                'id_cliente' => $obCliente->id,
                'carros_registrados' => $total_carros_registrados,
            ]);
        }

        return $itens;
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

            $acumulador = 0;
            foreach ($homens as $key => $value) {
                $acumulador += $value['idade'];
            }
            $media_homens = $acumulador / $qtd_homens;


            $mulheres = (new Database('proprietarios'))->select('sexo = "' . 2 . '"');
            $qtd_mulheres = (new Database('proprietarios'))->select('sexo = "' . 2 . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $acumulador_mulheres = 0;
            foreach ($mulheres as $key => $value) {
                $acumulador_mulheres += $value['idade'];
            }
            $media_mulheres = $acumulador_mulheres / $qtd_mulheres;


            $card_table = View::render('views/admin/includes/cliente/table_sexo/table_sexo', [
                'itens_homens' => self::getSexoHomemClientesItems(),
                'itens_mulheres' => self::getSexoMulherClientesItems(),
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
}
