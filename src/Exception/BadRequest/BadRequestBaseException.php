<?php

declare(strict_types=1);

namespace App\Exception\BadRequest;

use Throwable;

class BadRequestBaseException extends \Exception
{
    public const PARAMETER_NOT_FOUND = 2000;
    public const PARAMETER_NOT_ENOUGH = 2001;
    public const ANSWER_NOT_EXISTS_IN_QUESTION = 2002;

    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
