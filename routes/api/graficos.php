<?php 

use \App\Http\Response;
use \App\Controller\Api;


// GET
$router->get('/dashboard/graficos/charts/data/marcasmaisutilizadas',[
    'middlewares' => [
        'api',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Api\Graficos::getMarcasMaisUtilizadas($request),'application/json');
    }
]);

// GET
$router->get('/dashboard/graficos/charts/data/marcasmaisrevisoes',[
    'middlewares' => [
        'api',
        'required-admin-login',
    ],
    function($request){
        return new Response(200,Api\Graficos::getMarcasMaisRevisoes($request),'application/json');
    }
]);

