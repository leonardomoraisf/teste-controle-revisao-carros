<?php

date_default_timezone_set('America/Sao_Paulo');

include('includes/app.php');

use \App\Http\Router;

// INIT ROUTER
$router = new Router(URL);

// INCLUDE PUBLIC ROUTES
include('routes/public.php');

// INCLUDE ADMIN ROUTES
include('routes/admin.php');

// PRINT RESPONSE OF ROUTE
$router->run()
        ->sendResponse();
