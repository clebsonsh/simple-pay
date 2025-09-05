<?php

namespace App\Exceptions;

use Exception;

class NotificationServiceUnavailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'Notification service is not available');
    }
}
