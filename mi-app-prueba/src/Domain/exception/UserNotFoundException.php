<?php
namespace App\Domain;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = "El usuario no fue encontrado.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}