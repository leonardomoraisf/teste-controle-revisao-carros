<?php

namespace App\Controller\Admin;

use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use App\Model\Entity\Marca;


class Marcas extends Page
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
            case 'deletada':
                return Alert::getSuccess("Marca deletada com sucesso!");
                break;
            case 'erro':
                return Alert::getError("Essa marca possui carros registrados!");
                break;
            case 'sucesso':
                return Alert::getSuccess("Marca deletada com sucesso!");
                break;
        }
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasPageItems($request)
    {
        // marcas
        $itens = '';

        $results = (new Database('marcas'))->select(null, 'qtd_total DESC');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens .= View::render('views/admin/includes/marcas/itens_marcas', [
                'nome_marca' => $obMarca->nome,
                'qtd_marca' => $obMarca->qtd_total,
                'id_marca' => $obMarca->id,
            ]);
        }

        return $itens;
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasHomemPageItems($request)
    {
        // marcas
        $itens = '';

        $results = (new Database('marcas'))->select('qtd_homem > 0', 'qtd_homem DESC', '3');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens .= View::render('views/admin/includes/marcas/boxes/item', [
                'nome_marca' => $obMarca->nome,
                'qtd_marca' => $obMarca->qtd_homem,
            ]);
        }

        return $itens;
    }

    /**
     * Method to catch marcas render
     * @param Request $request
     * @return string
     */
    private static function getMarcasMulherPageItems($request)
    {
        // marcas
        $itens = '';

        $results = (new Database('marcas'))->select('qtd_mulher > 0', 'qtd_mulher DESC', '3');

        // RENDER ITEM
        while ($obMarca = $results->fetchObject(Marca::class)) {
            $itens .= View::render('views/admin/includes/marcas/boxes/item', [
                'nome_marca' => $obMarca->nome,
                'qtd_marca' => $obMarca->qtd_mulher,
            ]);
        }

        return $itens;
    }

    /**
     * Method to return home view
     * @return string
     */
    public static function getFormMarca($request, $errorMessage = null, $successMessage = null)
    {
        $statusError = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $statusSuccess = !is_null($successMessage) ? Alert::getSuccess($successMessage) : '';

        $elements = parent::getElements();
        return View::render('views/admin/marcas', [
            'links' => $elements['links'],
            'sidebar' => $elements['sidebar'],
            'header' => $elements['header'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Marcas',
            'user_name' => $_SESSION['user']['name'],
            'status' => self::getStatus($request),
            'statusError' => $statusError,
            'statusSuccess' => $statusSuccess,
            'active_nova_marca' => 'active',
            'itens_marcas' => self::getMarcasPageItems($request),
            'box_quantidade_marca_homem' => View::render('views/admin/includes/marcas/boxes/box_quantidade_marca_homem', [
                'itens_homens' => self::getMarcasHomemPageItems($request),
            ]),
            'box_quantidade_marca_mulher' => View::render('views/admin/includes/marcas/boxes/box_quantidade_marca_mulher', [
                'itens_mulheres' => self::getMarcasMulherPageItems($request),
            ]),
        ]);
    }

    public static function setFormMarca($request)
    {
        $postVars = $request->getPostVars();

        $marca = $postVars['nome'];

        if (!isset($postVars['nome']) || $marca == '') {
            return self::getFormMarca($request, 'Há campos nulos!');
        }

        $obMarca = new Marca;
        $obMarca->nome = $marca;
        $obMarca->qtd_homem = 0;
        $obMarca->qtd_mulher = 0;
        $obMarca->qtd_total = 0;
        $obMarca->register();

        // REDIRECT
        return self::getFormMarca($request, null, 'Nova marca criada com sucesso!');
    }

    public static function setDelete($request, $id)
    {
        $obMarca = Marca::getMarcaById($id);

        if (!$obMarca instanceof Marca) {
            $request->getRouter()->redirect('/dashboard/marcas');
        }
        if ($obMarca->qtd_total > 0) {
            $request->getRouter()->redirect('/dashboard/marcas?status=erro');
        }

        $obMarca->delete();

        $request->getRouter()->redirect('/dashboard/marcas?status=sucesso');
    }
}
