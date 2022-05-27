<?php

namespace App\Models;

class Token
{
    private int $id;
    
    private string $value;
    
    public function __construct()
    {
        $this->id       = 1;
        $this->value    = bin2hex(random_bytes(16));
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getId(): int
    {
        return $this->id;
    }
}