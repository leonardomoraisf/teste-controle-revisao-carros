<?php 

use \App\Http\Response;
use \App\Controller\Relatorio;

// GET
$router->get('/dashboard/relatorio/geral',[
    'middlewares' => [
        'pdf',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Relatorio\Geral::getGeral($request),'application/pdf');
    }
]);

// GET
$router->get('/dashboard/relatorio/clientes',[
    'middlewares' => [
        'pdf',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Relatorio\Geral::getClientes($request),'application/pdf');
    }
]);

// GET
$router->get('/dashboard/relatorio/carros',[
    'middlewares' => [
        'pdf',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Relatorio\Geral::getCarros($request),'application/pdf');
    }
]);

// GET
$router->get('/dashboard/relatorio/revisoes',[
    'middlewares' => [
        'pdf',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Relatorio\Geral::getRevisoes($request),'application/pdf');
    }
]);