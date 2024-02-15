<?php

namespace App\Exception\NotFound;

final class QuizImageNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Quiz image with id "%s" not found.', $id), NotFoundBaseException::QUIZ_IMAGE_NOT_FOUND, $previous);
    }
}
