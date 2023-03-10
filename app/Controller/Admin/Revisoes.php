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
            case 'erro':
                return Alert::getError("Essa revisão não existe!");
                break;
            case 'deletada':
                return Alert::getSuccess("Revisão deletada com sucesso!");
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
        $status = $postVars['status'];
        $detalhes = $postVars['detalhes'];

        if ($data == '' || $tipo == '' || $detalhes == '' || $status == '') {
            return self::getFormRevisao($request, $id, 'Há campos nulos!');
        }

        $obRevisao = new Revisao;
        $obRevisao->id_carro = $obCarro->id;
        $obRevisao->data = $data;
        $obRevisao->tipo = $tipo;
        $obRevisao->detalhes = $detalhes;
        $obRevisao->status = $status;
        $obRevisao->register();

        $obCarro->ultima_revisao = $data;
        $obCarro->update();

        $request->getRouter()->redirect('/dashboard/revisoes/' . $obCarro->id . '/carro');
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
                'id_revisao' => $obRevisao->id,
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
        return View::render('views/admin/revisoes/revisoes', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Revisões',
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

    public static function getRevisoesCarroItems($request, $id)
    {
        $itens = '';

        $obCarro = Carro::getCarroById($id);
        if (!$obCarro instanceof Carro) {
            return self::getRevisoes($request, 'Este carro não existe!');
        }

        // TOTAL REG QUANTITY
        $totalQuantity = (new Database('revisoes'))->select('id_carro = "' . $obCarro->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        // ACTUAL PAGE
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        // PAGINATION INSTANCE
        $obPagination = new Pagination($totalQuantity, $actualPage, 10);

        // RESULTS
        $results = (new Database('revisoes'))->select('id_carro = "' . $obCarro->id . '"', null, $obPagination->getLimit());

        // RENDER ITEM
        while ($obRevisao = $results->fetchObject(Revisao::class)) {
            $status = 'Pendente';
            if ($obRevisao->status == 1)
                $status = 'Concluída';

            $itens .= View::render('views/admin/includes/revisoes_carro/table_geral/table_item_geral', [
                'id_revisao' => $obRevisao->id,
                'data_revisao' => date('d/m/Y', strtotime($obRevisao->data)),
                'status_revisao' => $status,
                'detalhes_revisao' => $obRevisao->detalhes,
                'tipo_revisao' => Revisao::$tipos[$obRevisao->tipo],
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getRevisoesCarro($request, $id, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $obCarro = Carro::getCarroById($id);
        if (!$obCarro instanceof Carro) {
            return self::getRevisoes($request, 'Este carro não existe!');
        }

        $obMarca = Marca::getMarcaById($obCarro->id_marca);
        $obCliente = Cliente::getUserById($obCarro->id_proprietario);

        $results = (new Database('revisoes'))->select('id_carro = "' . $obCarro->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        if ($results != 0) {
            $card_table = View::render('views/admin/includes/revisoes_carro/table_geral/table_geral', [
                'itens' => self::getRevisoesCarroItems($request, $id),
            ]);
        } else {
            $card_table = View::render('views/admin/includes/revisoes_carro/table_non/item');
        }

        $elements = parent::getElements();
        return View::render('views/admin/revisoes/revisoes_carro', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'nome_marca' => $obMarca->nome,
            'nome_cliente' => $obCliente->name,
            'id_carro' => $obCarro->id,
            'title' => 'Revisões',
            'user_name' => $_SESSION['user']['name'],
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'active_revisoes' => 'active',
            'card_table' => $card_table,
            'status' => self::getStatus($request),
            'pagination' => (isset($obPagination)) ? parent::getPagination($request, $obPagination) : '',
        ]);
    }

    public static function setDelete($request, $id)
    {
        $obRevisao = Revisao::getRevisaoById($id);

        if (!$obRevisao instanceof Revisao) {
            $request->getRouter()->redirect('/dashboard/revisoes?status=erro');
        }

        $obCarro = Carro::getCarroById($obRevisao->id_carro);
        $results = (new Database('revisoes'))->select('id_carro = "' . $obCarro->id . '"', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        if ($results > 1) {
            $obUltima_revisao = (new Database('revisoes'))->select('id_carro = "' . $obCarro->id . '"', 'data DESC', '1')->fetchObject();
            $obCarro->ultima_revisao = $obUltima_revisao->data;
            $obCarro->update();

            $obRevisao->delete();

            $request->getRouter()->redirect('/dashboard/revisoes?status=deletada');
        }

        $obCarro->ultima_revisao = '';
        $obCarro->update();

        $obRevisao->delete();

        $request->getRouter()->redirect('/dashboard/revisoes?status=deletada');
    }
}
