<?php

namespace App\Exception\NotFound;

final class AnswerImageNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Answer image with id "%s" not found.', $id), NotFoundBaseException::ANSWER_IMAGE_NOT_FOUND, $previous);
    }
}
