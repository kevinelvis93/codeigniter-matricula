<?php

namespace App\Controllers;

class ErrorController extends BaseController
{
    public function noPermission()
    {
        return view('errors/no_permission'); // Carga la vista personalizada
    }
}
