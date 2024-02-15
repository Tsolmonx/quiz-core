<?php

namespace App\Exception\NotFound;

final class UserNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('User with id "%s" not found.', $id), NotFoundBaseException::USER_NOT_FOUND, $previous);
    }
}
