<?php

declare(strict_types=1);

namespace App\Exception\NotFound;

use Throwable;

class NotFoundBaseException extends \Exception
{
    public const ANSWER_NOT_FOUND = 1000;
    public const QUIZ_NOT_FOUND = 1001;
    public const QUESTION_NOT_FOUND = 1002;
    public const USER_NOT_FOUND = 1003;
    public const QUIZ_IMAGE_NOT_FOUND = 1004;
    public const QUESTION_IMAGE_NOT_FOUND = 1005;
    public const ANSWER_IMAGE_NOT_FOUND = 1006;
    public const USER_IMAGE_NOT_FOUND = 1007;
    public const QUESTION_GROUP_NOT_FOUND = 1008;

    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
