<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedTransferException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'you are not authorized to make this transfer');
    }
}
