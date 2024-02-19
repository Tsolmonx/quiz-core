<?php

namespace App\Exception\BadRequest;

final class ParameterNotEnoughException extends BadRequestBaseException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Parameter not found. "%s".', $message), BadRequestBaseException::PARAMETER_NOT_ENOUGH, $previous);
    }
}
