<?php

namespace App\Exception\NotFound;

final class QuestionNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Question with id "%s" not found.', $id), NotFoundBaseException::QUESTION_NOT_FOUND, $previous);
    }
}
