<?php 

use \App\Http\Response;
use \App\Controller\Admin;


// GET
$router->get('/dashboard/carros',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Admin\Carros::getCarros($request));
    }
]);

// GET
$router->get('/dashboard/forms/{id}/carro',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Carros::getFormCarro($request,$id));
    }
]);

// POST
$router->post('/dashboard/forms/{id}/carro',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Carros::setFormCarro($request,$id));
    }
]);