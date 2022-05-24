<?php

namespace App\Controllers;

use App\Models\Token;
use App\Router\Request;

class MainController
{
    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    #[Request('/main/{id}/', 'main_index')]
    public function index(Token $token, string $value, int $num) 
    {
        return ['url' => '/main', 'name' => 'mainIndex', 'value' => 'someValue'];
    }

    #[Request('/main/list', 'main_list')]
    public function many() 
    {
        echo 'MainControllers.many';
    }
}