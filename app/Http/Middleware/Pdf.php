<?php

namespace App\Http\Middleware;
use Exception;

class Pdf
{

    /**
     * Method to execute the middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next){

        // CHANGE CONTENT TYPE TO PDF
        $request->getRouter()->setContentType('application/pdf');

        // EXECUTE NEXT MIDDLEWARE LEVEL
        return $next($request);

    }

}
