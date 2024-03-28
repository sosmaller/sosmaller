<?php

namespace App\Controllers;

use App\Traits\BaseTrait;

class BaseController
{
    use BaseTrait;
    protected $request;

    public function __construct()
    {
        $this->init();
    }
}