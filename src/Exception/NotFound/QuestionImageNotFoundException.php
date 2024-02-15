<?php

namespace App\Exception\NotFound;

final class QuestionImageNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Question image with id "%s" not found.', $id), NotFoundBaseException::QUESTION_IMAGE_NOT_FOUND, $previous);
    }
}
