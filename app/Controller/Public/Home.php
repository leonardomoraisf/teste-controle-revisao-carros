<?php

namespace App\Controller\Public;

use App\Utils\View;

class Home extends Page
{

    /**
     * Method to return home view
     * @return string
     */
    public static function getHome($request)
    {
        $elements = parent::getElements();
        return View::render('views/public/home', [
            'links' => $elements['links'],
            'header' => $elements['header'],
            'footer' => $elements['footer'],
            'scriptlinks' => $elements['scriptlinks'],
            'title' => 'Página inicial',
            'home_active' => 'active',
            'secao_descritiva' => View::render('views/public/includes/home/secao_descritiva'),
        ]);
    }
}
