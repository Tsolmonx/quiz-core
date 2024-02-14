<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuizService
{
    public function __construct(
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter
    ) {
    }

    public function createQuestion(Quiz $quiz, array $params): Question
    {
        $group = $this->iriConverter->getResourceFromIri($params['questionGroup']);
        $question = new Question();
        $question->setQuiz($quiz);
        $question->setQuestionGroup($group);
        $question->setName($params['name']);
        $question->setPosition($params['position']);

        foreach ($params['answers'] as $answerParams) {
            $answer = $this->createAnswer($answerParams);
            $question->addAnswer($answer);
        }

        $this->em->persist($question);
        $this->em->flush();
        return $question;
    }

    public function createAnswer(array $params): Answer
    {
        $answer = new Answer();
        $answer->setPosition($params['position']);
        $answer->setValue($params['value']);
        $answer->setIsRightAnswer($params['isRightAnswer']);

        $this->em->persist($answer);

        return $answer;
    }
}
