<?php

namespace App\Exception\NotFound;

final class UserImageNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('User image with id "%s" not found.', $id), NotFoundBaseException::USER_IMAGE_NOT_FOUND, $previous);
    }
}
