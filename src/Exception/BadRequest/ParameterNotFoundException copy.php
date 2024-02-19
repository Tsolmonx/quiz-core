<?php

namespace App\Exception\BadRequest;

final class ParameterNotFoundException extends BadRequestBaseException
{
    public function __construct(string $parameter, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Parameter with key "%s" not found.', $parameter), BadRequestBaseException::PARAMETER_NOT_FOUND, $previous);
    }
}
