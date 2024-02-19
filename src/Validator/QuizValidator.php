<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\NotFound\ParameterNotEnoughException;
use App\Exception\NotFound\ParameterNotFoundException;

class QuizValidator
{
    public function validateSubmitQuizAnswers(array $array)
    {
        if (count($array) === 0) {
            throw new ParameterNotEnoughException('Quiz answer parameter is not enough');
        }

        foreach ($array as $params) {
            $questionIri = array_key_exists('questionId', $params) ? $params['questionId'] : null;
            $answers = array_key_exists('answers', $params) ? $params['answers'] : null;

            $questionIri === null && empty($questionIri) && throw new ParameterNotFoundException('questionId');

            // if (!filter_var($questionIri, FILTER_VALIDATE_URL)) {
            //     throw new InvalidIdentifierException('Question id is not valid IRI');
            // }

            foreach ($answers as $answerIri) {
                $answerIri === null && empty($answerIri) && throw new ParameterNotFoundException('answerId');
                // if (!filter_var($answerIri, FILTER_VALIDATE_URL)) {
                //     throw new InvalidIdentifierException('Answer id is not valid IRI');
                // }
            }
        }
    }
}
