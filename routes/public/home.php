<?php 

use \App\Http\Response;
use \App\Controller\Public;

// GET
$router->get('/',[
    'middlewares' => [
    ],
    function($request){
        return new Response(200,Public\Home::getHome($request));
    }
]);
