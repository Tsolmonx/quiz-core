<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\QuestionGroup;
use App\Entity\Quiz;

class QuestionGroupFactory
{
    public function createWithQuiz(Quiz $quiz): QuestionGroup
    {
        $group = new QuestionGroup();
        $quiz->addQuestionGroup($group);

        return $group;
    }
}
