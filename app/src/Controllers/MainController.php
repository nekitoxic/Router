<?php

namespace App\Controllers;

use App\Models\Token;
use App\Router\Endpoint;

class MainController
{
    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    #[Endpoint('/main/{id}/company/{value}/{num}', 'main_index')]
    public function index(Token $token, string $value, int $num) 
    {
        return ['url' => '/main', 'name' => 'mainIndex', 'value' => 'someValue'];
    }

    #[Endpoint('/main/list', 'main_list')]
    public function many() 
    {
        echo 'MainControllers.many';
    }
}