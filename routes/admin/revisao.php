<?php 

use \App\Http\Response;
use \App\Controller\Admin;

// REVISÃO
// GET
$router->get('/dashboard/forms/{id}/revisao',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Revisoes::getFormRevisao($request,$id));
    }
]);

// POST
$router->post('/dashboard/forms/{id}/revisao',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Revisoes::setFormRevisao($request,$id));
    }
]);

// GET
$router->get('/dashboard/revisoes',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Revisoes::getRevisoes($request));
    }
]);

// GET
$router->get('/dashboard/revisoes/{id}/carro',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Revisoes::getRevisoesCarro($request,$id));
    }
]);

// GET
$router->get('/dashboard/revisoes/{id}/delete',[
    'middlewares' => [
        'required-admin-login',
    ],
    function($request,$id){
        return new Response(200,Admin\Revisoes::setDelete($request,$id));
    }
]);