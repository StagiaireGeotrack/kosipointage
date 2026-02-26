<?php

namespace App\Services;

class HashService
{
    public function toHash(string $string): string
    {
        return hash('sha256', $string);
    }
}