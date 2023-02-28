<?php 

use \App\Http\Response;
use \App\Controller\Admin;

// GET LOGIN VIEW
$router->get('/dashboard/login',[
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200,Admin\Sessao::getLogin($request));
    }
]);

// POST FOR LOGIN
$router->post('/dashboard/login',[
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200,Admin\Sessao::setLogin($request));
    }
]);

// GET REG VIEW
$router->get('/dashboard/cadastro',[
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200,Admin\Sessao::getRegister($request));
    }
]);

// POST FOR REG
$router->post('/dashboard/cadastro',[
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200,Admin\Sessao::setRegister($request));
    }
]);

// GET LOGOUT
$router->get('/dashboard/logout',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Sessao::setLogout($request));
    }
]);