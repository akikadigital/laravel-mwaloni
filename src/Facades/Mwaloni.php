<?php

namespace Akika\LaravelMwaloni\Facades;

use Illuminate\Support\Facades\Facade;

class Mwaloni extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-mwaloni';
    }
}
