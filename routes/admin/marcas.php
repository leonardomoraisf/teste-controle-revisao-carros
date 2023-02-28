<?php 

use \App\Http\Response;
use \App\Controller\Admin;

// MARCA
// GET
$router->get('/dashboard/marcas',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Marcas::getFormMarca($request));
    }
]);

// POST
$router->post('/dashboard/marcas',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Marcas::setFormMarca($request));
    }
]);