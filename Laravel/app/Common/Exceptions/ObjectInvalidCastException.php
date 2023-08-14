<?php

namespace App\Common\Exceptions;


class ObjectInvalidCastException extends \Exception
{

    public function __construct($className, string $message = null, $code = 0, \Throwable $throwable = null)
    {
        parent::__construct($className . ($message ?? ' class was not matched'), $code, $throwable);
    }
}
