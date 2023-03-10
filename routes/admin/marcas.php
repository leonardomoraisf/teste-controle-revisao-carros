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

// POST
$router->get('/dashboard/marcas/{id}/delete',[
    'middlewares' => [
    'required-admin-login',
],
function($request,$id){
    return new Response(200,Admin\Marcas::setDelete($request,$id));
}
]);