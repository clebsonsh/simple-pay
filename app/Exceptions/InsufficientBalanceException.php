<?php

namespace App\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'The payer does not have enough balance to send this transfer');
    }
}
