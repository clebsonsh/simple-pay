<?php

namespace App\Exceptions;

use Exception;

class WrongUserTypeException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'The payer can not be a user type merchant');
    }
}
