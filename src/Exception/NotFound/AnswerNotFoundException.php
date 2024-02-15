<?php

namespace App\Exception\NotFound;

final class AnswerNotFoundException extends NotFoundBaseException
{
    public function __construct(string $id, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Answer with id "%s" not found.', $id), NotFoundBaseException::ANSWER_NOT_FOUND, $previous);
    }
}
