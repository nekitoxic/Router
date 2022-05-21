<?php

namespace App\Controllers;

use App\Router\Request;

class MainController
{
    #[Request('/main', 'main_index')]
    public function index() 
    {
        return ['url' => '/main', 'name' => 'mainIndex', 'value' => 'someValue'];
    }

    #[Request('/main/list', 'main_list')]
    public function many() 
    {
        echo 'MainControllers.many';
    }
}