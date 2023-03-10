<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\Pagination;
use App\Model\Entity\Proprietario as Cliente;
use App\Model\Entity\Carro;
use App\Model\Entity\Marca;

class Carros extends Page
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
                return Alert::getError("Esse carros não existe!");
                break;
            case 'deletado':
                return Alert::getSuccess("Carro e revisões deletadas!");
                break;
        }
    }

    /**
     * Method to catch items render
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getGeralCarrosItems($request, &$obPagination)
    {
        // Categories
        $itens = '';

        // TOTAL REG QUANTITY
        $totalQuantity = (new Database('carros'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // ACTUAL PAGE
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        // PAGINATION INSTANCE
        $obPagination = new Pagination($totalQuantity, $actualPage, 10);

        if (!isset($queryParams['f'])) {
            // RESULTS
            $results = (new Database('carros'))->select(null, null, $obPagination->getLimit());

            // RENDER ITEM
            while ($obCarro = $results->fetchObject(Carro::class)) {
                $obCliente = Cliente::getUserById($obCarro->id_proprietario);
                $obMarca = Marca::getMarcaById($obCarro->id_marca);
                $itens .= View::render('views/admin/includes/carros/table_geral/table_item_geral', [
                    'nome_marca' => $obMarca->nome,
                    'nome_cliente' => $obCliente->name,
                    'ultima_revisao' => ($obCarro->ultima_revisao == '0000-00-00') ? 'Sem revisão' : date('d/m/Y', strtotime($obCarro->ultima_revisao)),
                    'id_carro' => $obCarro->id,
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
    private static function getPessoaCarrosItems($request, &$obPagination)
    {
        // Categories
        $itens = '';

        // TOTAL REG QUANTITY
        $totalQuantity = (new Database('carros'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // ACTUAL PAGE
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        // PAGINATION INSTANCE
        $obPagination = new Pagination($totalQuantity, $actualPage, 10);

        if (isset($queryParams['f']) && $queryParams['f'] == 'pessoa') {
            // RESULTS
            $results = (new Database('carros'))->select(null, 'id_proprietario ASC', $obPagination->getLimit());

            // RENDER ITEM
            while ($obCarro = $results->fetchObject(Carro::class)) {
                $obCliente = Cliente::getUserById($obCarro->id_proprietario);
                $obMarca = Marca::getMarcaById($obCarro->id_marca);
                $itens .= View::render('views/admin/includes/carros/table_pessoa/table_item_pessoa', [
                    'nome_marca' => $obMarca->nome,
                    'nome_cliente' => $obCliente->name,
                    'ultima_revisao' => ($obCarro->ultima_revisao == '0000-00-00') ? 'Sem revisão' : date('d/m/Y', strtotime($obCarro->ultima_revisao)),
                    'id_carro' => $obCarro->id,
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
    private static function getHomemCarrosItems($request)
    {
        $itens = '';

        // RESULTS
        $results = (new Database('carros'))->select(null, 'id_proprietario ASC');

        // RENDER ITEM
        while ($obCarro = $results->fetchObject(Carro::class)) {
            $obCliente = Cliente::getUserById($obCarro->id_proprietario);

            if ($obCliente->sexo == 2)
                continue;

            $obMarca = Marca::getMarcaById($obCarro->id_marca);
            $itens .= View::render('views/admin/includes/carros/table_pessoa/table_item_pessoa', [
                'nome_marca' => $obMarca->nome,
                'nome_cliente' => $obCliente->name,
                'ultima_revisao' => ($obCarro->ultima_revisao == '0000-00-00') ? 'Sem revisão' : date('d/m/Y', strtotime($obCarro->ultima_revisao)),
                'id_carro' => $obCarro->id,
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
    private static function getMulherCarrosItems($request)
    {
        $itens = '';

        // RESULTS
        $results = (new Database('carros'))->select(null, 'id_proprietario ASC');

        // RENDER ITEM
        while ($obCarro = $results->fetchObject(Carro::class)) {
            $obCliente = Cliente::getUserById($obCarro->id_proprietario);

            if ($obCliente->sexo == 1)
                continue;

            $obMarca = Marca::getMarcaById($obCarro->id_marca);
            $itens .= View::render('views/admin/includes/carros/table_pessoa/table_item_pessoa', [
                'nome_marca' => $obMarca->nome,
                'nome_cliente' => $obCliente->name,
                'ultima_revisao' => ($obCarro->ultima_revisao == '0000-00-00') ? 'Sem revisão' : date('d/m/Y', strtotime($obCarro->ultima_revisao)),
                'id_carro' => $obCarro->id,
            ]);
        }

        return $itens;
    }

    /**
     * Method to return carros view
     * @return string
     */
    public static function getCarros($request)
    {
        $queryParams = $request->getQueryParams();

        $active_geral = (!isset($queryParams['f'])) ? 'active' : '';
        $active_pessoa = (isset($queryParams['f']) && $queryParams['f'] == 'pessoa') ? 'active' : '';
        $active_sexo = (isset($queryParams['f']) && $queryParams['f'] == 'sexo') ? 'active' : '';

        if (!isset($queryParams['f'])) {
            $card_table = View::render('views/admin/includes/carros/table_geral/table_geral', [
                'itens' => self::getGeralCarrosItems($request, $obPagination),
            ]);
        }

        if (isset($queryParams['f']) && $queryParams['f'] == 'pessoa') {
            $card_table = View::render('views/admin/includes/carros/table_pessoa/table_pessoa', [
                'itens' => self::getPessoaCarrosItems($request, $obPagination),
            ]);
        }

        if (isset($queryParams['f']) && $queryParams['f'] == 'sexo') {
            $card_table = View::render('views/admin/includes/carros/table_sexo/table_sexo', [
                'itens_homens' => self::getHomemCarrosItems($request),
                'itens_mulheres' => self::getMulherCarrosItems($request),
            ]);
        }

        $elements = parent::getElements();
        return View::render('views/admin/carros', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Carros',
            'user_name' => $_SESSION['user']['name'],
            'active_carros' => 'active',
            'status' => self::getStatus($request),
            'active_geral' => $active_geral,
            'active_pessoa' => $active_pessoa,
            'active_sexo' => $active_sexo,
            'pagination' => (isset($obPagination)) ? parent::getPagination($request, $obPagination) : '',
            'card_table' => $card_table,
        ]);
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasItems($request)
    {
        // marcas
        $itens = '';

        $results = (new Database('marcas'))->select();

        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens .= View::render('views/admin/includes/marcas/item', [
                'id_marca' => $obMarca->id,
                'nome_marca' => $obMarca->nome,
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getFormCarro($request, $id, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $obCliente = Cliente::getUserById($id);
        if (!$obCliente instanceof Cliente) {
            $request->getRouter()->redirect('/dashboard');
        }

        $elements = parent::getElements();
        return View::render('views/admin/forms/forms_carro', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Carro para: {{nome_cliente}}',
            'user_name' => $_SESSION['user']['name'],
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'nome_cliente' => $obCliente->name,
            'marcas' => self::getMarcasItems($request),
        ]);
    }

    public static function setFormCarro($request, $id)
    {
        $obCliente = Cliente::getUserById($id);
        if (!$obCliente instanceof Cliente) {
            $request->getRouter()->redirect('/dashboard');
        }

        $postVars = $request->getPostVars();

        $marca = $postVars['marca'];

        if ($marca == '') {
            return self::getFormCarro($request, $id, 'Há campos nulos!');
        }

        $obCarro = new Carro;
        $obCarro->id_marca = $marca;
        $obCarro->id_proprietario = $id;
        $obCarro->ultima_revisao = '';
        $obCarro->register();

        $obMarca = Marca::getMarcaById($obCarro->id_marca);
        $obMarca->qtd_total += 1;

        if ($obCliente->sexo == 1)
            $obMarca->qtd_homem += 1;

        if ($obCliente->sexo == 2)
            $obMarca->qtd_mulher += 1;

        $obMarca->updateQtd();

        // REDIRECT
        $request->getRouter()->redirect('/dashboard/forms/' . $obCarro->id . '/revisao');
    }

    public static function setDelete($request, $id)
    {
        $obCarro = Carro::getCarroById($id);

        if (!$obCarro instanceof Carro) {
            $request->getRouter()->redirect('/dashboard/carros?status=erro');
        }

        $obCliente = Cliente::getUserById($obCarro->id_proprietario);

        (new Database('revisoes'))->delete('id_carro = "'.$obCarro->id.'"');

        $obCarro->delete($obCliente);

        $request->getRouter()->redirect('/dashboard/carros?status=deletado');
    }
}
