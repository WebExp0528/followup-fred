<?php
namespace App\Exceptions;

use Exception;

class AuthFailException extends Exception{
    public function __construct()
    {
        parent::__construct();
    }
}