<?php

namespace App\Exception\NotFound;

final class QuestionGroupNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Question Group with id "%s" not found.', $id), NotFoundBaseException::QUIZ_NOT_FOUND, $previous);
    }
}
