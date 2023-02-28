<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\Pagination;
use App\Model\Entity\Proprietario as Cliente;
use App\Model\Entity\Carro;
use App\Model\Entity\Marca;
use App\Model\Entity\Revisao;


class Revisoes extends Page
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
            case '':
                return Alert::getError("");
                break;
        }
    }

    public static function getTiposItems()
    {
        $itens = '';

        $results = Revisao::getTypes();

        foreach ($results as $key => $value) {
            $itens .= View::render('views/admin/includes/revisao/tipos_revisao', [
                'id_tipo' => $key,
                'nome_tipo' => $value
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getFormRevisao($request, $id, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $obCarro = Carro::getCarroById($id);
        if (!$obCarro instanceof Carro) {
            $request->getRouter()->redirect('/dashboard');
        }

        $obMarca = Marca::getMarcaById($obCarro->id_marca);

        $obCliente = Cliente::getUserById($obCarro->id_proprietario);
        if (!$obCliente instanceof Cliente) {
            $request->getRouter()->redirect('/dashboard');
        }

        $elements = parent::getElements();
        return View::render('views/admin/forms/forms_revisao', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Nova Revisão',
            'user_name' => $_SESSION['user']['name'],
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'marca_carro_cliente' => $obMarca->nome,
            'nome_cliente' => $obCliente->name,
            'tipos' => self::getTiposItems(),
        ]);
    }

    public static function setFormRevisao($request, $id)
    {
        $obCarro = Carro::getCarroById($id);
        if (!$obCarro instanceof Carro) {
            $request->getRouter()->redirect('/dashboard');
        }

        $postVars = $request->getPostVars();

        $data = $postVars['data'];
        $tipo = $postVars['tipo'];
        $detalhes = $postVars['detalhes'];

        if ($data == '' || $data == '0000-00-00' || $tipo == '' || $detalhes == '') {
            return self::getFormRevisao($request, 'Há campos nulos!');
        }

        $obRevisao = new Revisao;
        $obRevisao->id_carro = $obCarro->id;
        $obRevisao->data = $data;
        $obRevisao->tipo = $tipo;
        $obRevisao->detalhes = $detalhes;
        $obRevisao->register();

        $request->getRouter()->redirect('/dashboard/' . $obRevisao->id . '/revisao');
    }

    public static function getTableGeralItems($request)
    {
        $itens = '';

        // TOTAL REG QUANTITY
        $totalQuantity = (new Database('revisoes'))->select(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // ACTUAL PAGE
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        // PAGINATION INSTANCE
        $obPagination = new Pagination($totalQuantity, $actualPage, 10);

        // RESULTS
        $results = (new Database('revisoes'))->select(null, 'id ASC', $obPagination->getLimit());

        // RENDER ITEM
        while ($obRevisao = $results->fetchObject(Revisao::class)) {
            $status = 'Pendente';
            if ($obRevisao->status == 1)
                $status = 'Concluída';

            $obCarro = Carro::getCarroById($obRevisao->id_carro);
            $obMarca = Marca::getMarcaById($obCarro->id_marca);
            $obCliente = Cliente::getUserById($obCarro->id_proprietario);
            
            $itens .= View::render('views/admin/includes/revisao/table_geral/table_item_geral', [
                'nome_cliente' => $obCliente->name,
                'nome_carro' => $obMarca->nome,
                'data_revisao' => date('d/m/Y', strtotime($obRevisao->data)),
                'status_revisao' => $status
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getRevisoes($request, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $queryParams = $request->getQueryParams();

        $active_geral = (!isset($queryParams['f'])) ? 'active' : '';
        $active_marcas = (isset($queryParams['f']) && $queryParams['f'] == 'marcas') ? 'active' : '';
        $active_pessoas = (isset($queryParams['f']) && $queryParams['f'] == 'pessoas') ? 'active' : '';

        if (!isset($queryParams['f'])) {
            $card_table = View::render('views/admin/includes/revisao/table_geral/table_geral', [
                'itens' => self::getTableGeralItems($request),
            ]);
        }

        $elements = parent::getElements();
        return View::render('views/admin/revisoes', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Nova Revisão',
            'user_name' => $_SESSION['user']['name'],
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'active_revisoes' => 'active',
            'card_table' => $card_table,
            'active_geral' => $active_geral,
            'active_marcas' => $active_marcas,
            'active_pessoas' => $active_pessoas,
            'status' => self::getStatus($request),
            'pagination' => (isset($obPagination)) ? parent::getPagination($request, $obPagination) : '',
        ]);
    }
}
