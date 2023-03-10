<?php 

use \App\Http\Response;
use \App\Controller\Admin;


// GET
$router->get('/dashboard/clientes',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Clientes::getClientes($request));
    }
]);

// GET
$router->get('/dashboard/clientes/{id}/carros',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Clientes::getClienteCarros($request,$id));
    }
]);

// GET
$router->get('/dashboard/forms/cliente',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Clientes::getFormCliente($request));
    }
]);

// POST
$router->post('/dashboard/forms/cliente',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Clientes::setFormCliente($request));
    }
]);

// POST
$router->get('/dashboard/clientes/{id}/delete',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Clientes::setDelete($request,$id));
    }
]);


