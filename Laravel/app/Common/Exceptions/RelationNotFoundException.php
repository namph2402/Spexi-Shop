<?php

namespace App\Common\Exceptions;


class RelationNotFoundException extends \Exception
{

    public function __construct(string $message = null, $code = 0, \Throwable $throwable = null)
    {
        parent::__construct($message ?? 'Relation not found', $code, $throwable);
    }
}
