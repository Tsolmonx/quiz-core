<?php

namespace App\Exception\BadRequest;

final class AnswerNotExistsInQuestionException extends BadRequestBaseException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Answer not exists in question. "%s".', $message), BadRequestBaseException::ANSWER_NOT_EXISTS_IN_QUESTION, $previous);
    }
}
