<?php

namespace App\Controller\Admin;

use App\Utils\View;
use \App\Model\Entity\UserOficina as User;
use \App\Session\Admin\Login as SessionAdminLogin;

class Sessao extends Page
{

    public static function getStatus($request)
    {
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // STATUS
        if (!isset($queryParams['status'])) return '';

        // STATUS MESSAGES
        switch ($queryParams['status']) {
            case 'sucesso':
                return Alert::getSuccess("Conta cadastrada!");
                break;
        }
    }

    /**
     * Method to return login view
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request, $errorMessage = null)
    {
        // status
        $error = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $elements = parent::getElements();
        return View::render('views/admin/sessao/login', [
            'preloader' => $elements['preloader'],
            'links' => $elements['links'],
            'scriptlinks' => $elements['scriptlinks'],
            'status' => $error,
            'status_middle' => self::getStatus($request),
        ]);
    }

    /**
     * Method to define the user login
     * @param Request $request
     */
    public static function setLogin($request)
    {
        // POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'];
        $password = $postVars['password'];

        // VALIDATIONS
        if ($email == '' || $password == '') {
            return self::getLogin($request, "There are empty fields!");
        }

        // SEARCH USER BY EMAIL
        $obUser = User::getUserByEmail($email);
        if (!$obUser instanceof User) {
            return self::getLogin($request, "Incorrect username or password!");
        }

        // CREATE LOGIN SESSION
        SessionAdminLogin::login($obUser);

        // REDIRECT TO HOME VIEW
        $request->getRouter()->redirect('/dashboard');
    }

    /**
     * Method to return reg view
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function getRegister($request, $errorMessage = null)
    {
        // status
        $error = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $elements = parent::getElements();
        return View::render('views/admin/sessao/register', [
            'preloader' => $elements['preloader'],
            'links' => $elements['links'],
            'scriptlinks' => $elements['scriptlinks'],
            'status' => $error,
            'status_middle' => self::getStatus($request),
        ]);
    }

    /**
     * Method to define the user reg
     * @param Request $request
     */
    public static function setRegister($request)
    {
        // POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'];
        $name = $postVars['name'];
        $password = $postVars['password'];
        $confirm_password = $postVars['confirm_password'];

        // VALIDATIONS
        if ($email == '' || $name == '' || $password == '' || $confirm_password == '') {
            return self::getRegister($request, "Há campos vazios!");
        }

        if ($password != $confirm_password) {
            return self::getRegister($request, "As senhas não coincidem!");
        }

        // SEARCH USER BY EMAIL
        $obUser = User::getUserByEmail($email) ? User::getUserByEmail($email) : '';
        if ($obUser instanceof User) {
            return self::getRegister($request, "Já existe uma conta com esse email!");
        }

        $obUser = new User;
        $obUser->email = $email;
        $obUser->name = $name;
        $obUser->password = $password;
        $obUser->register();

        // REDIRECT TO LOGIN VIEW
        $request->getRouter()->redirect('/dashboard/login?status=sucesso');
    }

    /**
     * Method to logout user
     * @param Request $request
     */
    public static function setLogout($request)
    {
        // DESTROY USER SESSION
        SessionAdminLogin::logout();

        // REDIRECT TO HOME VIEW
        $request->getRouter()->redirect('/dashboard');
    }
}
