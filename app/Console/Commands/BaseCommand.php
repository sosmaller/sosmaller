<?php

namespace App\Console\Commands;

use App\Traits\BaseTrait;

class BaseCommand
{
    use BaseTrait;

    public function __construct()
    {
        $this->init();
    }
}