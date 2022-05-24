<?php

namespace App\Models;

class Token
{
    private string $value;
    
    public function __construct()
    {
       $this->value = bin2hex(random_bytes(16));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}