<?php

namespace App\Exception\NotFound;

final class QuizNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Quiz with id "%s" not found.', $id), NotFoundBaseException::QUIZ_NOT_FOUND, $previous);
    }
}
