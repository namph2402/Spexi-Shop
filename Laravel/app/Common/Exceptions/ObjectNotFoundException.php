<?php

namespace App\Common\Exceptions;


class ObjectNotFoundException extends \Exception
{

    public function __construct(string $message = null, $code = 0, \Throwable $throwable = null)
    {
        parent::__construct($message ?? 'Object not found', $code, $throwable);
    }
}
